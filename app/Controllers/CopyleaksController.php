<?php

namespace App\Controllers;

use App\Models\PlagiarismModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Receives webhook callbacks from Copyleaks when a scan completes or fails.
 * Route: POST copyleaks/webhook/{status}
 *
 * IMPORTANT: This endpoint must be publicly reachable by Copyleaks' servers.
 * On localhost, use a tunnelling tool such as ngrok and update app.baseURL in .env.
 */
class CopyleaksController extends Controller
{
    public function initController(
        RequestInterface  $request,
        ResponseInterface $response,
        LoggerInterface   $logger
    ): void {
        parent::initController($request, $response, $logger);
    }

    /**
     * Copyleaks POSTs here with the scan status in the URL:
     *   completed  — scan done, results available
     *   error      — scan failed
     *   credits-expired — account out of credits
     */
    public function handle(string $status): ResponseInterface
    {
        $rawBody = $this->request->getBody() ?: '';
        $payload = json_decode($rawBody, true) ?? [];

        log_message('info', '[Copyleaks Webhook] status=' . $status . ' payload_size=' . strlen($rawBody));

        // Copyleaks sends scanId inside the payload
        $scanId = $payload['scannedDocument']['scanId']
               ?? $payload['scanId']
               ?? null;

        if (!$scanId) {
            log_message('warning', '[Copyleaks Webhook] No scanId in payload. Body: ' . substr($rawBody, 0, 500));
            return $this->response->setStatusCode(200)->setBody('ok');
        }

        $model  = new PlagiarismModel();
        $record = $model->getByScanId($scanId);

        if (!$record) {
            log_message('warning', '[Copyleaks Webhook] Unknown scanId: ' . $scanId);
            return $this->response->setStatusCode(200)->setBody('ok');
        }

        $now = date('Y-m-d H:i:s');

        if ($status === 'completed') {
            $score      = $payload['results']['score']['aggregatedScore']     ?? null;
            $identical  = $payload['results']['score']['identicalWords']      ?? null;
            $minor      = $payload['results']['score']['minorChangedWords']   ?? null;
            $paraphrase = $payload['results']['score']['relatedMeaningWords'] ?? null;

            $sources = array_merge(
                $payload['results']['internet']     ?? [],
                $payload['results']['database']     ?? [],
                $payload['results']['repositories'] ?? []
            );

            $model->updateByScanId($scanId, [
                'status'            => 'completed',
                'score'             => $score,
                'identical_pct'     => $identical,
                'minor_changed_pct' => $minor,
                'paraphrased_pct'   => $paraphrase,
                'sources_json'      => json_encode($sources),
                'webhook_raw'       => $rawBody,
                'completed_at'      => $now,
            ]);

            log_message('info', '[Copyleaks Webhook] Completed — scanId=' . $scanId . ' score=' . $score);
        } elseif ($status === 'error') {
            $model->updateByScanId($scanId, [
                'status'        => 'error',
                'error_message' => $payload['message'] ?? ('Error code: ' . ($payload['errorCode'] ?? 'unknown')),
                'webhook_raw'   => $rawBody,
                'completed_at'  => $now,
            ]);

            log_message('error', '[Copyleaks Webhook] Error — scanId=' . $scanId . ' msg=' . ($payload['message'] ?? ''));
        } else {
            // credits-expired, etc.
            $model->updateByScanId($scanId, [
                'status'      => str_replace('-', '_', $status),
                'webhook_raw' => $rawBody,
                'completed_at'=> $now,
            ]);
        }

        // Always return 200 — Copyleaks retries on non-200 responses
        return $this->response->setStatusCode(200)->setBody('ok');
    }
}

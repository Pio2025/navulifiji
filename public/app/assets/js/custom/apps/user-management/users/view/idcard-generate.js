"use strict";

var KTIdCardGenerate = (function () {
    var opts = {};
    var stream = null;
    var capturedDataUrl = null;

    function $id(id) {
        return document.getElementById(id);
    }

    function setChoice(choice) {
        var existingCard = $id("idcard_choice_existing");
        var newCard = $id("idcard_choice_new");
        var captureArea = $id("idcard_capture_area");

        if (existingCard) {
            existingCard.classList.toggle("active", choice === "existing");
        }
        if (newCard) {
            newCard.classList.toggle("active", choice === "new");
        }
        if (captureArea) {
            captureArea.classList.toggle("d-none", choice !== "new");
        }
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(function (t) { t.stop(); });
            stream = null;
        }
    }

    function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            Swal.fire({ text: "Camera access isn't available in this browser. Please upload a photo instead.", icon: "warning" });
            return;
        }
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
            .then(function (s) {
                stream = s;
                var video = $id("idcard_video");
                video.srcObject = s;
                $id("idcard_btn_start_cam").classList.add("d-none");
                $id("idcard_btn_capture").classList.remove("d-none");
            })
            .catch(function () {
                Swal.fire({ text: "Could not access the camera. Please check permissions or upload a photo instead.", icon: "error" });
            });
    }

    function capturePhoto() {
        var video = $id("idcard_video");
        var canvas = $id("idcard_canvas");
        var w = video.videoWidth || 480;
        var h = video.videoHeight || 600;

        // Crop to the ID card's photo-box ratio (20mm x 24mm) centered in the video frame.
        var targetRatio = 20 / 24;
        var srcW = w, srcH = h, srcX = 0, srcY = 0;
        if (w / h > targetRatio) {
            srcW = h * targetRatio;
            srcX = (w - srcW) / 2;
        } else {
            srcH = w / targetRatio;
            srcY = (h - srcH) / 2;
        }

        canvas.width = 300;
        canvas.height = 360;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(video, srcX, srcY, srcW, srcH, 0, 0, canvas.width, canvas.height);

        capturedDataUrl = canvas.toDataURL("image/jpeg", 0.85);
        showCapturedPreview(capturedDataUrl);

        stopCamera();
        video.srcObject = null;
        $id("idcard_btn_capture").classList.add("d-none");
        $id("idcard_btn_retake").classList.remove("d-none");
    }

    function retake() {
        capturedDataUrl = null;
        $id("idcard_captured_preview").classList.add("d-none");
        $id("idcard_btn_retake").classList.add("d-none");
        $id("idcard_btn_start_cam").classList.remove("d-none");
    }

    function showCapturedPreview(dataUrl) {
        var img = $id("idcard_captured_preview");
        img.src = dataUrl;
        img.classList.remove("d-none");

        var mainPreview = $id("idcard_preview_photo");
        if (mainPreview) {
            mainPreview.src = dataUrl;
        }
    }

    function handleFileUpload(e) {
        var file = e.target.files && e.target.files[0];
        if (!file) {
            return;
        }
        if (!/^image\/(png|jpeg)$/.test(file.type)) {
            Swal.fire({ text: "Please choose a PNG or JPG image.", icon: "warning" });
            return;
        }

        var reader = new FileReader();
        reader.onload = function (ev) {
            var img = new Image();
            img.onload = function () {
                var canvas = $id("idcard_canvas");
                canvas.width = 300;
                canvas.height = 360;
                var ctx = canvas.getContext("2d");

                var targetRatio = 20 / 24;
                var w = img.width, h = img.height;
                var srcW = w, srcH = h, srcX = 0, srcY = 0;
                if (w / h > targetRatio) {
                    srcW = h * targetRatio;
                    srcX = (w - srcW) / 2;
                } else {
                    srcH = w / targetRatio;
                    srcY = (h - srcH) / 2;
                }

                ctx.drawImage(img, srcX, srcY, srcW, srcH, 0, 0, canvas.width, canvas.height);
                capturedDataUrl = canvas.toDataURL("image/jpeg", 0.85);
                showCapturedPreview(capturedDataUrl);
            };
            img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    }

    function submitForm(e) {
        e.preventDefault();

        var choice = document.querySelector('input[name="photo_choice"]:checked');
        var useExisting = choice && choice.value === "existing";

        if (!useExisting && !capturedDataUrl) {
            Swal.fire({ text: "Please capture or upload a photo first.", icon: "warning" });
            return;
        }

        var btn = $id("idcard_btn_generate");
        var originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = "Generating...";

        var postData = {};
        postData[opts.csrfName] = opts.csrfHash;
        postData.use_existing = useExisting ? "1" : "0";
        if (!useExisting) {
            postData.photo_data = capturedDataUrl;
        }

        $.ajax({
            url: opts.saveUrl,
            method: "POST",
            data: postData,
            dataType: "json"
        }).done(function (resp) {
            if (resp && resp.success) {
                window.open(resp.redirect, "_blank");
                Swal.fire({ text: "ID card generated. It opened in a new tab.", icon: "success", timer: 2500, showConfirmButton: false });
            } else {
                Swal.fire({ text: (resp && resp.message) || "Could not generate the ID card.", icon: "error" });
            }
        }).fail(function (xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) || "Something went wrong. Please try again.";
            Swal.fire({ text: msg, icon: "error" });
        }).always(function () {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        });
    }

    return {
        init: function (options) {
            opts = options || {};

            document.querySelectorAll('input[name="photo_choice"]').forEach(function (radio) {
                radio.addEventListener("change", function () {
                    setChoice(radio.value);
                    if (radio.value !== "new") {
                        stopCamera();
                    }
                });
            });

            var startBtn = $id("idcard_btn_start_cam");
            var captureBtn = $id("idcard_btn_capture");
            var retakeBtn = $id("idcard_btn_retake");
            var fileInput = $id("idcard_file_input");
            var form = $id("kt_idcard_form");

            if (startBtn) startBtn.addEventListener("click", startCamera);
            if (captureBtn) captureBtn.addEventListener("click", capturePhoto);
            if (retakeBtn) retakeBtn.addEventListener("click", retake);
            if (fileInput) fileInput.addEventListener("change", handleFileUpload);
            if (form) form.addEventListener("submit", submitForm);

            window.addEventListener("beforeunload", stopCamera);
        }
    };
})();

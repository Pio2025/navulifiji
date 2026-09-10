<?php

namespace App\Controllers;

use App\Models\LibraryBookIssueModel;

class LibraryController extends BaseController
{
    // ================================================================
    // CATALOG
    // ================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Book Catalog', 'Library', 'Book Catalog');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_library_listing')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $schId      = $isSuperAdmin ? 0 : (int) $this->session->get('schID');
        $categoryId = (int) ($this->request->getGet('category_id') ?: 0);
        $search     = trim((string) $this->request->getGet('search'));

        $data['books']       = $this->libraryBookModel->getBySchool($schId, $categoryId ?: null, $search ?: null);
        $data['categories']  = $this->libraryCategoryModel->getBySchool($schId);
        $data['categoryId']  = $categoryId;
        $data['search']      = $search;
        $data['canAdd']      = $isSuperAdmin || $this->grant_access('_add_book');
        $data['canEdit']     = $isSuperAdmin || $this->grant_access('_edit_book');
        $data['canDelete']   = $isSuperAdmin || $this->grant_access('_remove_book');
        $data['canIssue']    = $isSuperAdmin || $this->grant_access('_issue_book');
        $data['_view']       = 'app/library/index';

        return view('app/layouts/main', $data);
    }

    public function add()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Add Book', 'Library', 'Book Catalog');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_add_book')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $schId = (int) $this->session->get('schID');

        $data['categories'] = $this->libraryCategoryModel->getBySchool($schId);
        $data['_view']      = 'app/library/form';

        return view('app/layouts/main', $data);
    }

    private function bookValidationRules(): array
    {
        return [
            'title'          => 'required|max_length[200]',
            'author'         => 'permit_empty|max_length[150]',
            'isbn'           => 'permit_empty|max_length[50]',
            'publisher'      => 'permit_empty|max_length[150]',
            'edition'        => 'permit_empty|max_length[50]',
            'shelf_location' => 'permit_empty|max_length[100]',
            'category_id'    => 'permit_empty|integer',
            'total_copies'   => 'required|integer|greater_than[0]',
            'description'    => 'permit_empty',
        ];
    }

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_add_book')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        if (!$this->validate($this->bookValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $schId = (int) $this->session->get('schID');
        if ($schId <= 0) {
            return redirect()->back()->withInput()
                ->with('error', 'No school context found for your account. Please contact an administrator.');
        }

        $isbn = trim((string) $this->request->getPost('isbn'));
        if ($isbn !== '' && $this->libraryBookModel->isbnExists($schId, $isbn)) {
            return redirect()->back()->withInput()
                ->with('error', "A book with ISBN \"{$isbn}\" already exists in the catalog.");
        }

        $totalCopies = (int) $this->request->getPost('total_copies');
        $categoryId  = (int) $this->request->getPost('category_id') ?: null;
        $title       = trim($this->request->getPost('title'));

        $this->libraryBookModel->insert([
            'sch_id_fk'        => $schId,
            'category_id_fk'   => $categoryId,
            'title'            => $title,
            'author'           => trim((string) $this->request->getPost('author')) ?: null,
            'isbn'             => $isbn ?: null,
            'publisher'        => trim((string) $this->request->getPost('publisher')) ?: null,
            'edition'          => trim((string) $this->request->getPost('edition')) ?: null,
            'shelf_location'   => trim((string) $this->request->getPost('shelf_location')) ?: null,
            'total_copies'     => $totalCopies,
            'available_copies' => $totalCopies,
            'description'      => trim((string) $this->request->getPost('description')) ?: null,
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('library')
            ->with('success', "Book \"{$title}\" added successfully.");
    }

    public function edit(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Edit Book', 'Library', 'Book Catalog');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_edit_book')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $book = $this->libraryBookModel->getDetail($id);
        if (!$book) {
            return redirect()->to('library')->with('error', 'Book not found.');
        }

        $data['book']       = $book;
        $data['categories'] = $this->libraryCategoryModel->getBySchool((int) $book['sch_id_fk']);
        $data['_view']      = 'app/library/form';

        return view('app/layouts/main', $data);
    }

    public function update(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_edit_book')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $book = $this->libraryBookModel->find($id);
        if (!$book) {
            return redirect()->to('library')->with('error', 'Book not found.');
        }

        if (!$this->validate($this->bookValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $isbn = trim((string) $this->request->getPost('isbn'));
        if ($isbn !== '' && $this->libraryBookModel->isbnExists((int) $book['sch_id_fk'], $isbn, $id)) {
            return redirect()->back()->withInput()
                ->with('error', "A book with ISBN \"{$isbn}\" already exists in the catalog.");
        }

        $newTotal    = (int) $this->request->getPost('total_copies');
        $db          = \Config\Database::connect();
        $issuedCount = $db->table('library_book_issue')
            ->where('book_id_fk', $id)
            ->where('status', 'Issued')
            ->countAllResults();

        if ($issuedCount > $newTotal) {
            return redirect()->back()->withInput()
                ->with('error', "Cannot set total copies below the number currently issued ({$issuedCount}).");
        }

        $categoryId = (int) $this->request->getPost('category_id') ?: null;
        $title      = trim($this->request->getPost('title'));

        $this->libraryBookModel->update($id, [
            'category_id_fk'   => $categoryId,
            'title'            => $title,
            'author'           => trim((string) $this->request->getPost('author')) ?: null,
            'isbn'             => $isbn ?: null,
            'publisher'        => trim((string) $this->request->getPost('publisher')) ?: null,
            'edition'          => trim((string) $this->request->getPost('edition')) ?: null,
            'shelf_location'   => trim((string) $this->request->getPost('shelf_location')) ?: null,
            'total_copies'     => $newTotal,
            'available_copies' => $newTotal - $issuedCount,
            'description'      => trim((string) $this->request->getPost('description')) ?: null,
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('library')
            ->with('success', "Book \"{$title}\" updated successfully.");
    }

    public function delete(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_remove_book')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $book = $this->libraryBookModel->find($id);
        if (!$book) {
            return redirect()->to('library')->with('error', 'Book not found.');
        }

        $historyCount = count($this->libraryBookIssueModel->getHistoryByBook($id));
        if ($historyCount > 0) {
            return redirect()->to('library')
                ->with('error', "Cannot delete \"{$book['title']}\" — it has {$historyCount} issue record(s) on file.");
        }

        $this->libraryBookModel->delete($id);

        return redirect()->to('library')
            ->with('success', "Book \"{$book['title']}\" deleted successfully.");
    }

    public function detail(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Book Detail', 'Library', 'Book Catalog');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_book_detail')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $book = $this->libraryBookModel->getDetail($id);
        if (!$book) {
            return redirect()->to('library')->with('error', 'Book not found.');
        }

        $data['book']    = $book;
        $data['history'] = $this->libraryBookIssueModel->getHistoryByBook($id);
        $data['_view']   = 'app/library/detail';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // ISSUE / RETURN
    // ================================================================

    public function issue()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Issue / Return', 'Library', 'Issue / Return');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_issue_book')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $schId = $isSuperAdmin ? 0 : (int) $this->session->get('schID');

        $data['issued']    = $this->libraryBookIssueModel->getIssuedBySchool($schId);
        $data['books']     = $this->libraryBookModel->getAvailableBySchool($schId);
        $data['borrowers'] = $this->admissionModel->getActiveMembersBySchool($schId);
        $data['canReturn'] = $isSuperAdmin || $this->grant_access('_return_book');
        $data['_view']     = 'app/library/issue';

        return view('app/layouts/main', $data);
    }

    public function storeIssue()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_issue_book')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $bookId       = (int) $this->request->getPost('book_id');
        $admissionId  = (int) $this->request->getPost('borrower_admission_id');

        if ($bookId <= 0 || $admissionId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select both a book and a borrower.']);
        }

        $book = $this->libraryBookModel->find($bookId);
        if (!$book) {
            return $this->response->setJSON(['success' => false, 'message' => 'Book not found.']);
        }
        if ((int) $book['available_copies'] <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'No available copies of this book.']);
        }

        $admission = $this->admissionModel->find($admissionId);
        if (!$admission) {
            return $this->response->setJSON(['success' => false, 'message' => 'Borrower not found.']);
        }

        $issueDate = date('Y-m-d');
        $dueDate   = date('Y-m-d', strtotime($issueDate . ' + ' . LibraryBookIssueModel::LOAN_DAYS . ' days'));

        $this->libraryBookIssueModel->insert([
            'book_id_fk'               => $bookId,
            'borrower_admission_id_fk' => $admissionId,
            'sch_id_fk'                => (int) $book['sch_id_fk'],
            'issue_date'               => $issueDate,
            'due_date'                 => $dueDate,
            'status'                   => 'Issued',
            'issued_by'                => (int) $this->session->get('userID'),
            'created_at'               => date('Y-m-d H:i:s'),
            'updated_at'               => date('Y-m-d H:i:s'),
        ]);

        $this->libraryBookModel->decrementAvailable($bookId);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Book issued successfully. Due back on ' . $dueDate . '.',
            'redirect' => base_url('library/issue'),
        ]);
    }

    public function returnBook(int $issueId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_return_book')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $issue = $this->libraryBookIssueModel->find($issueId);
        if (!$issue || $issue['status'] !== 'Issued') {
            return redirect()->to('library/issue')->with('error', 'Issue record not found or already closed.');
        }

        $returnDate = date('Y-m-d');
        $fine       = LibraryBookIssueModel::calculateFine($issue['due_date'], $returnDate);
        $finePaid   = $this->request->getPost('fine_paid') ? 1 : 0;

        $this->libraryBookIssueModel->update($issueId, [
            'return_date' => $returnDate,
            'fine_amount' => $fine,
            'fine_paid'   => $finePaid,
            'status'      => 'Returned',
            'returned_by' => (int) $this->session->get('userID'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->libraryBookModel->incrementAvailable((int) $issue['book_id_fk']);

        $message = $fine > 0
            ? "Book returned. Overdue fine of $" . number_format($fine, 2) . ' recorded.'
            : 'Book returned successfully.';

        return redirect()->to('library/issue')->with('success', $message);
    }

    public function markLost(int $issueId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_issue_book')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $issue = $this->libraryBookIssueModel->find($issueId);
        if (!$issue || $issue['status'] !== 'Issued') {
            return redirect()->to('library/issue')->with('error', 'Issue record not found or already closed.');
        }

        $this->libraryBookIssueModel->update($issueId, [
            'status'      => 'Lost',
            'returned_by' => (int) $this->session->get('userID'),
            'remarks'     => trim((string) $this->request->getPost('remarks')) ?: 'Marked lost',
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('library/issue')->with('success', 'Book marked as lost.');
    }

    // ================================================================
    // MY LIBRARY — self-service (Student sees own, Parent sees children's)
    // ================================================================

    public function my()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $userID    = (int) $this->session->get('userID');
        $roleCatID = (int) $this->session->get('roleCatID');

        $isStudent = ($roleCatID === 4);
        $isParent  = ($roleCatID === 6);

        $isParentStaff = false;
        if (!$isStudent && !$isParent) {
            if ($this->grant_access('_my_library') || $this->grant_access('_my_child_library')) {
                $isParent = true;
            } else {
                $user = $this->userModel->find($userID);
                if (!empty($user['is_a_parent']) && (int) $user['is_a_parent'] === 1) {
                    $isParentStaff = true;
                    $isParent      = true;
                } else {
                    $data['_view'] = 'app/auth/access_control';
                    return view('app/layouts/main', $data);
                }
            }
        }

        $this->setPageData('My Library', 'Library', $isParent ? "Children's Library" : 'My Library');

        $data['isStudent'] = $isStudent;
        $data['isParent']  = $isParent || $isParentStaff;
        $data['admission'] = null;
        $data['issues']    = [];
        $data['children']  = [];

        if ($isStudent) {
            $admissions = $this->admissionModel->getAdmissionByUser($userID);
            $admission  = !empty($admissions) ? $admissions[0] : null;

            $data['admission'] = $admission;
            $data['issues']    = $admission
                ? $this->libraryBookIssueModel->getByBorrowers([(int) $admission['admission_id']])
                : [];
        } else {
            $children     = $this->parentStudentModel->getChildrenOf($userID);
            $childrenData = [];

            foreach ($children as $child) {
                $childId    = (int) $child['user_id'];
                $admissions = $this->admissionModel->getAdmissionByUser($childId);
                $admission  = !empty($admissions) ? $admissions[0] : null;

                $childrenData[] = [
                    'child'     => $child,
                    'admission' => $admission,
                    'issues'    => $admission
                        ? $this->libraryBookIssueModel->getByBorrowers([(int) $admission['admission_id']])
                        : [],
                ];
            }

            $data['children'] = $childrenData;
        }

        $data['_view'] = 'app/library/my';
        return view('app/layouts/main', $data);
    }
}

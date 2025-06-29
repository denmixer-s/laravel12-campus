<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any documents.
     */
    public function viewAny(User $user): bool
    {
        // Super Admin can view everything
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Staff can view documents in their department or public documents
        if ($user->user_type === 'staff') {
            return $user->can('documents.view') || $user->can('admin.access');
        }

        // Public users can view published public documents only
        return true; // Will be filtered by scope in queries
    }

    /**
     * Determine whether the user can view the document.
     */
    public function view(User $user, Document $document): bool
    {
        // Super Admin can view everything
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Document must be published to be viewable
        if ($document->status !== 'published') {
            // Only creator or admins can view unpublished documents
            return $this->isCreatorOrAdmin($user, $document);
        }

        // Check access level
        if ($document->access_level === 'public') {
            return true;
        }

        if ($document->access_level === 'registered') {
            return auth()->check();
        }

        // Department-specific access for staff
        if ($user->user_type === 'staff' && $user->department_id) {
            return $document->department_id === $user->department_id ||
                   $user->can('documents.view-all-departments');
        }

        return false;
    }

    /**
     * Determine whether the user can create documents.
     */
    public function create(User $user): bool
    {
        // Super Admin can create everything
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Only staff can create documents
        if ($user->user_type !== 'staff') {
            return false;
        }

        return $user->can('documents.create');
    }

    /**
     * Determine whether the user can update the document.
     */
    public function update(User $user, Document $document): bool
    {
        // Super Admin can update everything
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Only staff can update documents
        if ($user->user_type !== 'staff') {
            return false;
        }

        // Must have update permission
        if (!$user->can('documents.edit')) {
            return false;
        }

        // Creator can always update their own documents
        if ($document->created_by === $user->id) {
            return true;
        }

        // Admins can update any document
        if ($user->can('admin.access')) {
            return true;
        }

        // Department managers can update documents in their department
        if ($user->department_id && $document->department_id === $user->department_id) {
            return $user->can('documents.edit-department');
        }

        return false;
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document): bool
    {
        // Super Admin can delete everything
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Only staff can delete documents
        if ($user->user_type !== 'staff') {
            return false;
        }

        // Must have delete permission
        if (!$user->can('documents.delete')) {
            return false;
        }

        // Cannot delete published documents unless admin
        if ($document->status === 'published' && !$user->can('admin.access')) {
            return false;
        }

        // Creator can delete their own documents (if not published)
        if ($document->created_by === $user->id && $document->status !== 'published') {
            return true;
        }

        // Admins can delete any document
        if ($user->can('admin.access')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the document.
     */
    public function restore(User $user, Document $document): bool
    {
        // Super Admin can restore everything
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Only admins can restore documents
        return $user->can('admin.access') && $user->can('documents.restore');
    }

    /**
     * Determine whether the user can permanently delete the document.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        // Only Super Admin can force delete
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Or specific permission
        return $user->can('documents.force-delete') && $user->can('admin.access');
    }

    /**
     * Determine whether the user can publish the document.
     */
    public function publish(User $user, Document $document): bool
    {
        // Super Admin can publish everything
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Only staff can publish documents
        if ($user->user_type !== 'staff') {
            return false;
        }

        // Must have publish permission
        if (!$user->can('documents.publish')) {
            return false;
        }

        // Creator can publish their own documents
        if ($document->created_by === $user->id) {
            return true;
        }

        // Admins can publish any document
        if ($user->can('admin.access')) {
            return true;
        }

        // Department managers can publish documents in their department
        if ($user->department_id && $document->department_id === $user->department_id) {
            return $user->can('documents.publish-department');
        }

        return false;
    }

    /**
     * Determine whether the user can archive the document.
     */
    public function archive(User $user, Document $document): bool
    {
        // Super Admin can archive everything
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Only staff can archive documents
        if ($user->user_type !== 'staff') {
            return false;
        }

        // Must have archive permission
        if (!$user->can('documents.archive')) {
            return false;
        }

        // Creator can archive their own documents
        if ($document->created_by === $user->id) {
            return true;
        }

        // Admins can archive any document
        if ($user->can('admin.access')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can download the document.
     */
    public function download(?User $user, Document $document): bool
    {
        // Document must be published
        if ($document->status !== 'published') {
            return false;
        }

        // Check access level
        if ($document->access_level === 'public') {
            return true;
        }

        if ($document->access_level === 'registered') {
            return $user !== null;
        }

        // Staff can download documents from their department
        if ($user && $user->user_type === 'staff' && $user->department_id) {
            return $document->department_id === $user->department_id;
        }

        return false;
    }

    /**
     * Determine whether the user can view document statistics.
     */
    public function viewStats(User $user): bool
    {
        // Super Admin can view all stats
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->can('documents.view-stats');
    }

    /**
     * Determine whether the user can export documents/reports.
     */
    public function export(User $user): bool
    {
        // Super Admin can export everything
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->can('documents.export');
    }

    /**
     * Determine whether the user can manage document categories.
     */
    public function manageCategories(User $user): bool
    {
        // Super Admin can manage categories
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->can('categories.manage');
    }

    /**
     * Determine whether the user can manage document types.
     */
    public function manageTypes(User $user): bool
    {
        // Super Admin can manage types
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->can('document-types.manage');
    }

    /**
     * Determine whether the user can view document history.
     */
    public function viewHistory(User $user, Document $document): bool
    {
        // Super Admin can view all history
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Creator can view their own document history
        if ($document->created_by === $user->id) {
            return true;
        }

        // Admins can view any document history
        return $user->can('admin.access') && $user->can('documents.view-history');
    }

    /**
     * Determine whether the user can manage document versions.
     */
    public function manageVersions(User $user, Document $document): bool
    {
        // Super Admin can manage all versions
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Creator can manage versions of their own documents
        if ($document->created_by === $user->id) {
            return $user->can('documents.manage-versions');
        }

        // Admins can manage any document versions
        return $user->can('admin.access') && $user->can('documents.manage-versions');
    }

    /**
     * Helper method to check if user is creator or admin.
     */
    protected function isCreatorOrAdmin(User $user, Document $document): bool
    {
        return $document->created_by === $user->id ||
               $user->can('admin.access') ||
               $user->hasRole('Super Admin');
    }

    /**
     * Helper method to check department access.
     */
    protected function hasDepartmentAccess(User $user, Document $document): bool
    {
        // Super Admin has access to all departments
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Admin has access to all departments
        if ($user->can('admin.access')) {
            return true;
        }

        // Staff can access documents from their department
        if ($user->user_type === 'staff' && $user->department_id) {
            return $document->department_id === $user->department_id;
        }

        return false;
    }

    /**
     * Determine whether the user can bulk manage documents.
     */
    public function bulkManage(User $user): bool
    {
        // Super Admin can bulk manage
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->can('documents.bulk-manage') && $user->can('admin.access');
    }

    /**
     * Determine whether the user can feature/unfeature documents.
     */
    public function feature(User $user, Document $document): bool
    {
        // Super Admin can feature any document
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Must be admin to feature documents
        return $user->can('admin.access') && $user->can('documents.feature');
    }

    /**
     * Determine whether the user can change document access level.
     */
    public function changeAccessLevel(User $user, Document $document): bool
    {
        // Super Admin can change access level
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Creator can change access level of their own documents
        if ($document->created_by === $user->id) {
            return $user->can('documents.change-access-level');
        }

        // Admins can change access level of any document
        return $user->can('admin.access') && $user->can('documents.change-access-level');
    }
}

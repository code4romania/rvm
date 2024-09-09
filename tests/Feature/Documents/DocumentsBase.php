<?php

declare(strict_types=1);

namespace Tests\Feature\Documents;

use App\Enum\DocumentType;
use App\Enum\OrganisationStatus;
use App\Enum\UserRole;
use App\Filament\Resources\DocumentResource\Pages\EditDocument;
use App\Filament\Resources\DocumentResource\Pages\ListDocuments;
use App\Filament\Resources\DocumentResource\Pages\ViewDocument;
use App\Models\Document;
use App\Models\Organisation;
use App\Models\User;
use Database\Seeders\ResourceCategorySeed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;
use Tests\TestCase;

abstract class DocumentsBase extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = ResourceCategorySeed::class;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    protected function getOrganisationAdmin(): Collection
    {
        return User::query()
            ->role(UserRole::ORG_ADMIN)
            ->inRandomOrder()
            ->get();
    }

    protected function getOrganisationAdminWithInactiveOrganisation(): User
    {
        return Organisation::query()
            ->whereStatus(OrganisationStatus::inactive)
            ->with('users')
            ->inRandomOrder()
            ->first()
            ->users
            ->first();
    }

    protected function createOrganisations(int $count = 3, string $status = 'active'): void
    {
        if ($status === 'inactive') {
            Organisation::factory()
                ->count($count)
                ->inactive()
                ->withUserAndDocuments()
                ->createQuietly();

            return;
        }

        if ($status === 'random') {
            Organisation::factory()
                ->count($count)
                ->withUserAndDocuments()
                ->randomStatus()
                ->createQuietly();

            return;
        }

        Organisation::factory()
            ->count($count)
            ->withUserAndDocuments()
            ->createQuietly();
    }

    public function viewDocuments(): TestableLivewire
    {
        $documents = Document::all();

        return Livewire::test(ListDocuments::class)
            ->assertSuccessful()
            ->assertCountTableRecords(9)
            ->assertCanSeeTableRecords($documents)
            ->assertCanRenderTableColumn('name')
            ->filterTable('type', DocumentType::contract->value)
            ->assertCanSeeTableRecords($documents->where('type', DocumentType::contract))
            ->resetTableFilters()
            ->assertCanRenderTableColumn('organisation.name')
            ->sortTable('name')
            ->assertCanSeeTableRecords($documents->sortBy('name'), inOrder: true)
            ->assertCanRenderTableColumn('media.file_name')
            ->sortTable('media.file_name')
            ->assertCanSeeTableRecords($documents->sortBy('media.file_name'), inOrder: true)
            ->assertCanRenderTableColumn('signed_at')
            ->sortTable('signed_at')
            ->assertCanSeeTableRecords($documents->sortBy('signed_at'), inOrder: true)
            ->assertCanRenderTableColumn('expires_at')
            ->sortTable('expires_at')
            ->assertCanSeeTableRecords($documents->sortBy('expires_at'), inOrder: true);
    }

    public function viewProtocolDocumentByUser(Document $document): TestableLivewire
    {
        return Livewire::test(ViewDocument::class, ['record' => $document->id])
            ->assertSuccessful()
            ->assertFormFieldIsVisible('organisation_id')
            ->assertFormFieldIsVisible('name')
            ->assertFormFieldIsVisible('type')
            ->assertFormFieldIsVisible('signed_at')
            ->assertFormFieldIsVisible('expires_at')
            ->assertFormFieldIsVisible('never_expires')
            ->assertFormFieldIsVisible('document');
    }

    public function viewDocumentByUser(Document $document): TestableLivewire
    {
        return Livewire::test(ViewDocument::class, ['record' => $document->id])
            ->assertSuccessful()
            ->assertFormFieldIsVisible('organisation_id')
            ->assertFormFieldIsVisible('name')
            ->assertFormFieldIsVisible('type')
            ->assertFormFieldIsHidden('signed_at')
            ->assertFormFieldIsHidden('expires_at')
            ->assertFormFieldIsHidden('never_expires')
            ->assertFormFieldIsVisible('document');
    }

    public function editDocument(Document $document): TestableLivewire
    {
        return Livewire::test(EditDocument::class, ['record' => $document->id])
            ->assertSuccessful()
            ->assertFormFieldIsVisible('organisation_id')
            ->assertFormFieldIsEnabled('organisation_id')
            ->assertFormFieldIsVisible('name')
            ->assertFormFieldIsEnabled('name')
            ->assertFormFieldIsVisible('type')
            ->assertFormFieldIsEnabled('type')
            ->assertFormFieldIsVisible('name')
            ->assertFormFieldIsEnabled('name')
            ->assertFormFieldIsVisible('document')
            ->assertFormFieldIsEnabled('document');
    }
}

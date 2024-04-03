<?php

declare(strict_types=1);

namespace Tests\Feature\Documents;

use App\Enum\DocumentType;
use App\Enum\UserRole;
use App\Filament\Resources\DocumentResource;
use App\Models\Document;
use App\Models\User;
use Livewire;

class OrganisationAdminTest extends DocumentsBaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createOrganisations();
        $this->createOrganisations(2, 'inactive');
        $this->user = User::query()
            ->role(UserRole::ORG_ADMIN)
            ->inRandomOrder()
            ->first();
//        $this->actingAs($this->user);
    }

    public function testOrgAdminCanViewDocuments(): void
    {
        $this->actingAs($this->user);
        $userOganisationID = $this->user->organisation_id;
        $documents = Document::query()
            ->whereOrganisationId($userOganisationID)
            ->get();
        $documentsFromAnotherOrg = Document::query()
            ->whereNot('organisation_id', $userOganisationID)
            ->get();
        Livewire::test(DocumentResource\Pages\ListDocuments::class)
            ->assertSuccessful()
            ->assertPageActionHidden('create')
            ->assertPageActionDisabled('create')
            ->assertCountTableRecords(3)
            ->assertCanSeeTableRecords($documents)
            ->assertCanNotSeeTableRecords($documentsFromAnotherOrg)
            ->assertCanRenderTableColumn('name')
            ->filterTable('type', DocumentType::contract->value)
            ->assertCanSeeTableRecords($documents->where('type', DocumentType::contract))
            ->resetTableFilters()
            ->sortTable('name')
            ->assertCanSeeTableRecords($documents->sortBy('name'), inOrder: true)
            ->assertCanNotRenderTableColumn('organisation.name')
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

    public function testViewDocumentsByInactiveOrgAdmin(): void
    {
        $url = DocumentResource::getUrl('index');

        $this->createOrganisations(2, 'inactive');
        $orgAdmin = $this->getOrgAdminWithInactiveOrg(2);
        $this->actingAs($orgAdmin)
            ->get($url)
            ->assertRedirect('/login');
    }

    public function testOrgAdminCanViewDocument(): void
    {
        $document = $this->user
            ->organisation
            ->documents
            ->filter(fn ($item) => $item->type === DocumentType::protocol)
            ->first();
        $this->actingAs($this->user);

        $this->viewProtocolDocumentByUser($document)
            ->assertPageActionHidden('edit')
            ->assertPageActionHidden('delete')
            ->assertPageActionDisabled('edit')
            ->assertPageActionDisabled('delete');

        $document = $this->user
            ->organisation
            ->documents
            ->filter(fn ($item) => $item->type !== DocumentType::protocol)
            ->first();

        $this->viewDocumentByUser($document)
            ->assertPageActionHidden('edit')
            ->assertPageActionHidden('delete')
            ->assertPageActionDisabled('edit')
            ->assertPageActionDisabled('delete');
    }

    public function testInactiveOrgAdminCanNotViewDocument(): void
    {
        $this->createOrganisations(2, 'inactive');
        $orgAdmins = $this->getOrgAdminWithInactiveOrg();

        $document = Document::query()
            ->inRandomOrder()
            ->first();
        $url = DocumentResource::getUrl('view', ['record' => $document->id]);

        $this->actingAs($orgAdmins)
            ->withSession(['banned' => false])
            ->get($url)
            ->assertRedirect('/login');
    }

    public function testAnotherOrgAdminCanNotViewDocument(): void
    {
//        dd(Document::all()->count());
        $document = Document::query()
            ->whereNot('organisation_id', $this->user->organisation_id)
            ->inRandomOrder()
            ->first();
//        dd($this->user->organisation_id, $document);

        Livewire::test(DocumentResource\Pages\ViewDocument::class, ['record' => $document->id])
            ->assertForbidden();
    }

    public function testAnotherInactiveOrgAdminCanNotViewDocument(): void
    {
        $this->createOrganisations(2, 'inactive');
        $orgAdmin = $this->getOrgAdminWithInactiveOrg();

        $document = Document::query()
            ->whereNot('organisation_id', $orgAdmin->organisation_id)
            ->inRandomOrder()
            ->first();

        $url = DocumentResource::getUrl('view', ['record' => $document->id]);

        $this->actingAs($orgAdmin)
            ->withSession(['banned' => false])
            ->get($url)
            ->assertRedirect('/login');
    }

    public function testOrgAdminCanNotEditDocument(): void
    {
        $document = $this->user
            ->organisation
            ->documents
            ->first();

        Livewire::test(DocumentResource\Pages\EditDocument::class, ['record' => $document->id])
            ->assertForbidden();
    }

    public function testInactiveOrgAdminCanNotEditDocument(): void
    {
        $this->createOrganisations(2, 'inactive');
        $orgAdmin = $this->getOrgAdminWithInactiveOrg();

        $document = Document::query()
            ->where('organisation_id', $orgAdmin->organisation_id)
            ->inRandomOrder()
            ->first();
        $url = DocumentResource::getUrl('edit', ['record' => $document->id]);

        $this->actingAs($orgAdmin)
            ->withSession(['banned' => false])
            ->get($url)
            ->assertRedirect('/login');
    }

    public function testAnotherOrgAdminCanNotEditDocument(): void
    {
        $document = Document::query()
            ->whereNot('organisation_id', $this->user->organisation_id)
            ->inRandomOrder()
            ->first();

        $url = DocumentResource::getUrl('edit', ['record' => $document->id]);

        $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->get($url)
            ->assertNotFound();
    }

    public function testAnotherInactiveOrgAdminCanNotEditDocument(): void
    {
        $this->createOrganisations(2, 'inactive');
        $orgAdmin = $this->getOrgAdminWithInactiveOrg();

        $document = Document::query()
            ->whereNot('organisation_id', $orgAdmin->organisation_id)
            ->inRandomOrder()
            ->first();

        $url = DocumentResource::getUrl('edit', ['record' => $document->id]);

        $this->actingAs($orgAdmin)
            ->withSession(['banned' => false])
            ->get($url)
            ->assertRedirect('/login');
    }

    public function testInactiveOrgAdminCanNotDeleteDocument(): void
    {
        $this->createOrganisations(2, 'inactive');
        $orgAdmin = $this->getOrgAdminWithInactiveOrg();

        $document = Document::query()
            ->where('organisation_id', $orgAdmin->organisation_id)
            ->inRandomOrder()
            ->first();

        Livewire::actingAs($orgAdmin);
        Livewire::test(DocumentResource\Pages\ViewDocument::class, ['record' => $document->id])
            ->assertPageActionDisabled('delete');
    }

    public function testOrgAdminCanNotCreateDocument()
    {
        $orgAdmins = $this->getOrgAdmin()->random();

        Livewire::actingAs($orgAdmins);
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->assertForbidden();
    }

    public function testInactiveOrgAdminCanNotCreateDocument()
    {
        $this->createOrganisations(2, 'inactive');
        $orgAdmins = $this->getOrgAdminWithInactiveOrg();

        Livewire::actingAs($orgAdmins);
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->assertForbidden();
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enum\DocumentType;
use App\Enum\UserRole;
use App\Filament\Resources\DocumentResource;
use App\Models\Document;
use App\Models\Organisation;
use App\Models\User;
use Database\Seeders\ResourceCategorySeed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class DocumentsTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = ResourceCategorySeed::class;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    protected function getPlatformAdmins(): User
    {
        return User::factory()
            ->platformAdmin()
            ->create();
    }

    protected function getPlatformCoordinator(): User
    {
        return User::factory()
            ->platformCoordinator()
            ->create();
    }

    protected function getOrgAdminWithActiveOrg(): Collection
    {
        Organisation::factory()
            ->count(5)
            ->withRelated()
            ->createQuietly();

        return User::query()
            ->withoutGlobalScopes()
            ->role(UserRole::ORG_ADMIN)
            ->get();
    }

    protected function getOrgAdminWithInactiveOrg(): Collection
    {
        Organisation::factory()
            ->count(5)
            ->inactive()
            ->withRelated()
            ->createQuietly();

        return User::query()
            ->withoutGlobalScopes()
            ->role(UserRole::ORG_ADMIN)
            ->get();
    }

    protected function createOrganisations(): void
    {
        Organisation::factory()
            ->count(5)
            ->randomStatus()
            ->withRelated()
            ->createQuietly();
    }

    public function testPlatformAdminsCanListAllDocuments(): void
    {
        $user = User::factory()
            ->platformAdmin()
            ->create();

        $organisation = Organisation::factory()
            ->withRelated()
            ->createQuietly();

        $documents = Document::all();

        $this->actingAs($user);

        Livewire::test(DocumentResource\Pages\ListDocuments::class)
            ->assertSuccessful()
            ->assertCountTableRecords(3)
            ->assertCanSeeTableRecords($documents)
            ->assertCanRenderTableColumn('name')
            ->filterTable('type', DocumentType::contract->value)
            ->assertCanSeeTableRecords($documents->where('type', DocumentType::contract))
            ->resetTableFilters()
            ->sortTable('name')
            ->assertCanSeeTableRecords($documents->sortBy('name'), inOrder: true)
            ->assertCanRenderTableColumn('organisation.name')
            ->sortTable('organisation.name')
            ->assertCanSeeTableRecords($documents->sortBy('organisation.name'), inOrder: true)
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

    public function testPlatformCoordinatorCanListAllDocuments(): void
    {
    }

    public function testOrganisationAdminCanListOwnDocuments(): void
    {
    }

    public function testOrganisationAdminCannotListOtherDocuments(): void
    {
    }

    public function testViewDocumentsByOrgAdmin(): void
    {
        $url = DocumentResource::getUrl('index');

        $orgAdmin = $this->getOrgAdminWithActiveOrg()->random();
        $this->actingAs($orgAdmin)
            ->withSession(['banned' => false])
            ->get($url)
            ->assertSuccessful();
    }

    public function testViewDocumentsByInactiveOrgAdmin(): void
    {
        $url = DocumentResource::getUrl('index');

        $orgAdmin = $this->getOrgAdminWithInactiveOrg()->random();
        $this->actingAs($orgAdmin)
            ->withSession(['banned' => false])
            ->get($url)
            ->assertFound();
    }

    public function testViewDocumentsByPlatformCoordinator(): void
    {
        $url = DocumentResource::getUrl('index');
        $this->actingAs($this->getPlatformCoordinator())
            ->withSession(['banned' => false])
            ->get($url)
            ->assertSuccessful();
    }

    public function testViewDocumentsByPlatformAdmin(): void
    {
        $url = DocumentResource::getUrl('index');
        $this->actingAs($this->getPlatformAdmins())
            ->withSession(['banned' => false])
            ->get($url)
            ->assertSuccessful();
    }


    public function testViewDocumentByPlatformAdmin(): void
    {
        $this->createOrganisations();
        $document = Document::query()
            ->inRandomOrder()
            ->first();

        $url = DocumentResource::getUrl('view', ['record' => $document->id]);
        $this->actingAs($this->getPlatformAdmins())
            ->withSession(['banned' => false])
            ->get($url)
            ->assertSuccessful();
    }

    public function testViewDocumentByPlatformCoordinator(): void
    {
        $this->createOrganisations();
        $document = Document::query()
            ->inRandomOrder()
            ->first();

        $url = DocumentResource::getUrl('view', ['record' => $document->id]);

        $this->actingAs($this->getPlatformCoordinator())
            ->withSession(['banned' => false])
            ->get($url)
            ->assertSuccessful();
    }

    public function testViewDocumentByOrgAdmin()
    {
        $orgAdmins = $this->getOrgAdminWithActiveOrg();

        $document = Document::query()
            ->inRandomOrder()
            ->first();
        $url = DocumentResource::getUrl('view', ['record' => $document->id]);
        $documentUser = $orgAdmins->filter(fn ($item) => $item->organisation_id == $document->organisation_id)->first();

        $this->actingAs($documentUser)
            ->withSession(['banned' => false])
            ->get($url)
            ->assertSuccessful();
    }

    public function testViewDocumentByInactiveOrgAdmin()
    {
        $orgAdmins = $this->getOrgAdminWithInactiveOrg();

        $document = Document::query()
            ->inRandomOrder()
            ->first();
        $url = DocumentResource::getUrl('view', ['record' => $document->id]);
        $documentUser = $orgAdmins->filter(fn ($item) => $item->organisation_id == $document->organisation_id)->first();

        $this->actingAs($documentUser)
            ->withSession(['banned' => false])
            ->get($url)
            ->assertFound();
    }

    public function testViewDocumentByAnotherOrgAdmin(): void
    {
        $orgAdmins = $this->getOrgAdminWithActiveOrg();

        $document = Document::query()
            ->inRandomOrder()
            ->first();

        $url = DocumentResource::getUrl('view', ['record' => $document->id]);

        $this->actingAs($orgAdmins->filter(fn ($item) => $item->organisation_id != $document->organisation_id)->first())
            ->withSession(['banned' => false])
            ->get($url)
            ->assertNotFound();
    }

    public function testViewDocumentByAnotherInactiveOrgAdmin(): void
    {
        $orgAdmins = $this->getOrgAdminWithInactiveOrg();

        $document = Document::query()
            ->inRandomOrder()
            ->first();

        $url = DocumentResource::getUrl('view', ['record' => $document->id]);

        $this->actingAs($orgAdmins->filter(fn ($item) => $item->organisation_id != $document->organisation_id)->first())
            ->withSession(['banned' => false])
            ->get($url)
            ->assertFound();
    }

    public function testEditDocumentByPlatformAdmin(): void
    {
        $this->createOrganisations();
        $document = Document::query()
            ->inRandomOrder()
            ->first();

        $url = DocumentResource::getUrl('edit', ['record' => $document->id]);
        $this->actingAs($this->getPlatformAdmins())
            ->withSession(['banned' => false])
            ->get($url)
            ->assertSuccessful();
    }

    public function testEditDocumentByPlatformCoordinator(): void
    {
        $this->createOrganisations();
        $document = Document::query()
            ->inRandomOrder()
            ->first();

        $url = DocumentResource::getUrl('edit', ['record' => $document->id]);

        $this->actingAs($this->getPlatformCoordinator())
            ->withSession(['banned' => false])
            ->get($url)
            ->assertForbidden();
    }

    public function testEditDocumentByOrgAdmin()
    {
        $orgAdmins = $this->getOrgAdminWithActiveOrg();

        $document = Document::query()
            ->inRandomOrder()
            ->first();
        $url = DocumentResource::getUrl('edit', ['record' => $document->id]);
        $documentUser = $orgAdmins->filter(fn ($item) => $item->organisation_id == $document->organisation_id)->first();

        $this->actingAs($documentUser)
            ->withSession(['banned' => false])
            ->get($url)
            ->assertForbidden();
    }

    public function testEditDocumentByInactiveOrgAdmin()
    {
        $orgAdmins = $this->getOrgAdminWithInactiveOrg();

        $document = Document::query()
            ->inRandomOrder()
            ->first();
        $url = DocumentResource::getUrl('edit', ['record' => $document->id]);
        $documentUser = $orgAdmins->filter(fn ($item) => $item->organisation_id == $document->organisation_id)->first();

        $this->actingAs($documentUser)
            ->withSession(['banned' => false])
            ->get($url)
            ->assertFound();
    }

    public function testEditDocumentByAnotherOrgAdmin(): void
    {
        $orgAdmins = $this->getOrgAdminWithActiveOrg();

        $document = Document::query()
            ->inRandomOrder()
            ->first();

        $url = DocumentResource::getUrl('edit', ['record' => $document->id]);

        $this->actingAs($orgAdmins->filter(fn ($item) => $item->organisation_id != $document->organisation_id)->first())
            ->withSession(['banned' => false])
            ->get($url)
            ->assertNotFound();
    }

    public function testEditDocumentByAnotherInactiveOrgAdmin(): void
    {
        $orgAdmins = $this->getOrgAdminWithInactiveOrg();

        $document = Document::query()
            ->inRandomOrder()
            ->first();

        $url = DocumentResource::getUrl('edit', ['record' => $document->id]);

        $this->actingAs($orgAdmins->filter(fn ($item) => $item->organisation_id != $document->organisation_id)->first())
            ->withSession(['banned' => false])
            ->get($url)
            ->assertFound();
    }

    public function testDeleteDocumentByPlatformAdmin(): void
    {
        $this->createOrganisations();
        $document = Document::query()
            ->inRandomOrder()
            ->first();

        Livewire::actingAs($this->getPlatformAdmins());
        Livewire::test(DocumentResource\Pages\ViewDocument::class, ['record' => $document->id])
            ->callPageAction('delete')
            ->assertSuccessful();
    }

    public function testDeleteDocumentByPlatformCoordinator(): void
    {
        $this->createOrganisations();
        $document = Document::query()
            ->inRandomOrder()
            ->first();

        Livewire::actingAs($this->getPlatformCoordinator());
        Livewire::test(DocumentResource\Pages\ViewDocument::class, ['record' => $document->id])
            ->assertPageActionDisabled('delete');
    }

    public function testDeleteDocumentByOrgAdmin()
    {
        $orgAdmins = $this->getOrgAdminWithActiveOrg();

        $document = Document::query()
            ->inRandomOrder()
            ->first();
        $documentUser = $orgAdmins->filter(fn ($item) => $item->organisation_id == $document->organisation_id)->first();

        Livewire::actingAs($documentUser);
        Livewire::test(DocumentResource\Pages\ViewDocument::class, ['record' => $document->id])
            ->assertPageActionDisabled('delete');
    }

    public function testDeleteDocumentByInactiveOrgAdmin()
    {
        $orgAdmins = $this->getOrgAdminWithInactiveOrg();

        $document = Document::query()
            ->inRandomOrder()
            ->first();
        $documentUser = $orgAdmins->filter(fn ($item) => $item->organisation_id == $document->organisation_id)->first();

        Livewire::actingAs($documentUser);
        Livewire::test(DocumentResource\Pages\ViewDocument::class, ['record' => $document->id])
            ->assertPageActionDisabled('delete');
    }

    public function testDeleteDocumentByAnotherOrgAdmin(): void
    {
        $this->assertTrue(true);

    //        $orgAdmins = $this->getOrgAdminWithActiveOrg();
    //
    //        $document = Document::query()
    //            ->inRandomOrder()
    //            ->first();
    //
    //        $documentUser = $orgAdmins->filter(fn($item) => $item->organisation_id != $document->organisation_id)->first();
    //        $params = ['record' => $document->id];
    //
    //        \Livewire::actingAs($documentUser);
    //        \Livewire::test(DocumentResource\Pages\ViewDocument::class, $params)
    //            ->assertPageActionDisabled('delete');
    }

    public function testDeleteDocumentByAnotherInactiveOrgAdmin(): void
    {
        $this->assertTrue(true);

    //        $orgAdmins = $this->getOrgAdminWithInactiveOrg();
    //
    //        $document = Document::query()
    //            ->inRandomOrder()
    //            ->first();
    //
    //        $url = DocumentResource::getUrl('edit', ['record' => $document->id]);
    //
    //        $documentUser = $orgAdmins->filter(fn($item) => $item->organisation_id != $document->organisation_id)->first();
    //        $params = ['record' => $document->id];
    //        \Livewire::actingAs($documentUser);
    //        \Livewire::test(DocumentResource\Pages\ViewDocument::class, $params)
    //            ->assertPageActionDisabled('delete');
    }

    public function testCreateDocumentByPlatformAdmin(): void
    {
        Livewire::actingAs($this->getPlatformAdmins());
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->assertSuccessful();
    }

    public function testCreateDocumentByPlatformCoordinator(): void
    {
        Livewire::actingAs($this->getPlatformCoordinator());
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->assertForbidden();
    }

    public function testCreateDocumentByOrgAdmin()
    {
        $orgAdmins = $this->getOrgAdminWithActiveOrg()->random();

        Livewire::actingAs($orgAdmins);
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->assertForbidden();
    }

    public function testCreateDocumentByInactiveOrgAdmin()
    {
        $orgAdmins = $this->getOrgAdminWithInactiveOrg()->random();

        Livewire::actingAs($orgAdmins);
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->assertForbidden();
    }

    public function testCreateDocument()
    {
        $this->createOrganisations();

        $file = \Illuminate\Http\UploadedFile::fake()
            ->image(fake()->word() . '.jpg')
            ->store('tests');

        // all data ok
        Livewire::actingAs($this->getPlatformAdmins());
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::contract,
                'document' => [$file]])
            ->call('create')
            ->assertHasNoFormErrors();

        // wrong organisation_id
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->max('id') + 1,
                'name' => fake()->word(),
                'type' => DocumentType::contract,
                'document' => [$file]])
            ->call('create')
            ->assertHasFormErrors(['organisation_id']);

        // name over 255
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->realTextBetween(256, 300),
                'type' => DocumentType::contract,
                'document' => [$file]])
            ->call('create')
            ->assertHasFormErrors(['name']);

        // wrong type
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => fake()->word(),
                'document' => [$file]])
            ->call('create')
            ->assertHasFormErrors(['type']);

        // hidden signed_at, expired_at and never_expires when document type is contract
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::contract,
                'document' => [$file]])
            ->assertFormFieldIsHidden('signed_at')
            ->assertFormFieldIsHidden('expires_at')
            ->assertFormFieldIsHidden('never_expires');

        // hidden signed_at, expired_at and never_expires when document type is other
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::other,
                'document' => [$file]])
            ->assertFormFieldIsHidden('signed_at')
            ->assertFormFieldIsHidden('expires_at')
            ->assertFormFieldIsHidden('never_expires');

        // visible signed_at, expired_at and never_expires
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::protocol,
                'document' => [$file]])
            ->assertFormFieldIsVisible('signed_at')
            ->assertFormFieldIsVisible('expires_at')
            ->assertFormFieldIsVisible('never_expires');

        // document type protocol without start/end date
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::protocol,
                'document' => [$file]])
            ->call('create')
            ->assertHasFormErrors(['signed_at' => 'required',
                'expires_at' => 'required',
            ]);

        // document type protocol with never expires and without start/end date
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::protocol,
                'document' => [$file],
                'never_expires' => true])
            ->call('create')
            ->assertHasFormErrors(['signed_at' => 'required']);

        // document type protocol with never expires and without start/end date
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::protocol,
                'document' => [$file],
                'never_expires' => true])
            ->call('create')
            ->assertHasFormErrors(['signed_at' => 'required']);

        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::protocol,
                'document' => [$file],
                'signed_at' => fake()->date(),
                'never_expires' => true])
            ->call('create')
            ->assertHasNoFormErrors();

        $endDate = fake()->date();
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::protocol,
                'document' => [$file],
                'signed_at' => fake()->date(max: $endDate),
                'expires_at' => $endDate])
            ->call('create')
            ->assertHasNoFormErrors();

        $startDate = fake()->date();
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::protocol,
                'document' => [$file],
                'signed_at' => $startDate,
                'expires_at' => fake()->date(max: $startDate)])
            ->call('create')
            ->assertHasFormErrors(['expires_at']);
    }
}

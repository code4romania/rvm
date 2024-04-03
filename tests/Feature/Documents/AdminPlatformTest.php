<?php

declare(strict_types=1);

namespace Tests\Feature\Documents;

use App\Enum\DocumentType;
use App\Filament\Resources\DocumentResource\Pages\CreateDocument;
use App\Filament\Resources\DocumentResource\Pages\ViewDocument;
use App\Models\Document;
use App\Models\Organisation;
use App\Models\User;
use Livewire;

class AdminPlatformTest extends DocumentsBaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()
            ->platformAdmin()
            ->create();
        Livewire::actingAs($this->user);

        $this->createOrganisations(3, 'random');
    }

    public function testAdminPlatformCanViewDocuments(): void
    {
        $this->viewDocuments()
            ->assertPageActionVisible('create')
            ->assertPageActionEnabled('create');
    }

    public function testViewAdminPlatformCanViewDocument(): void
    {
        $document = Document::query()
            ->whereType(DocumentType::protocol)
            ->first();

        $this->viewProtocolDocumentByUser($document)
            ->assertPageActionVisible('edit')
            ->assertPageActionVisible('delete');

        $document = Document::query()
            ->whereNot('type', DocumentType::protocol)
            ->first();

        $this->viewDocumentByUser($document)
            ->assertPageActionVisible('edit')
            ->assertPageActionVisible('delete');
    }

    public function testAdminPlatformCanEditDocument(): void
    {
        $protocolDocument = Document::query()
            ->whereType(DocumentType::protocol)
            ->first();

        $this->editDocument($protocolDocument)
            ->assertFormFieldIsVisible('signed_at')
            ->assertFormFieldIsEnabled('signed_at')
            ->assertFormFieldIsVisible('expires_at')
            ->assertFormFieldIsEnabled('expires_at')
            ->assertFormFieldIsVisible('never_expires')
            ->assertFormFieldIsEnabled('never_expires');

        $document = Document::query()
            ->whereNot('type', DocumentType::protocol)
            ->first();

        $this->editDocument($document)
            ->assertFormFieldIsHidden('signed_at')
            ->assertFormFieldIsHidden('expires_at')
            ->assertFormFieldIsHidden('never_expires');
    }

    public function testAdminPlatformCanDeleteDocument(): void
    {
        $document = Document::query()
            ->inRandomOrder()
            ->first();

        Livewire::test(ViewDocument::class, ['record' => $document->id])
            ->callPageAction('delete')
            ->assertSuccessful();
        $this->assertNull(Document::find($document->id));
    }

    public function testAdminPlatformCanCreateDocument()
    {
        $file = \Illuminate\Http\UploadedFile::fake()
            ->image(fake()->word() . '.jpg')
            ->store('tests');

        // all data ok for type contract
        Livewire::test(CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::contract,
                'document' => [$file]])
            ->assertFormFieldIsHidden('signed_at')
            ->assertFormFieldIsHidden('expires_at')
            ->assertFormFieldIsHidden('never_expires')
            ->call('create')
            ->assertHasNoFormErrors();

        // all data ok for protocol with never_expires
        Livewire::test(CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::protocol,
                'document' => [$file],
                'signed_at' => fake()->date(),
                'never_expires' => true])
            ->assertFormFieldIsVisible('signed_at')
            ->assertFormFieldIsVisible('expires_at')
            ->assertFormFieldIsVisible('never_expires')
            ->call('create')
            ->assertHasNoFormErrors();

        // all data ok for protocol with expire date
        $endDate = fake()->date();
        Livewire::test(CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::protocol,
                'document' => [$file],
                'signed_at' => fake()->date(max: $endDate),
                'expires_at' => $endDate])
            ->assertFormFieldIsVisible('signed_at')
            ->assertFormFieldIsVisible('expires_at')
            ->assertFormFieldIsVisible('never_expires')
            ->call('create')
            ->assertHasNoFormErrors();

        // all data ok for other type document
        Livewire::test(CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::other,
                'document' => [$file]])
            ->assertFormFieldIsHidden('signed_at')
            ->assertFormFieldIsHidden('expires_at')
            ->assertFormFieldIsHidden('never_expires')
            ->call('create')
            ->assertHasNoFormErrors();

        // wrong organisation_id, name, type
        Livewire::test(CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->max('id') + 1,
                'name' => fake()->word(),
                'type' => DocumentType::contract,
                'document' => [$file]])
            ->call('create')
            ->assertHasFormErrors(['organisation_id'])
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->realTextBetween(256, 300)])
            ->call('create')
            ->assertHasFormErrors(['name'])
            ->fillForm(['name' => fake()->word(),
                'type' => fake()->word(), ])
            ->call('create')
            ->assertHasFormErrors(['type']);

        // document type protocol without start/end date, expires_at before signed_at
        $startDate = fake()->date();
        Livewire::test(CreateDocument::class)
            ->fillForm(['organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
                'name' => fake()->word(),
                'type' => DocumentType::protocol,
                'document' => [$file]])
            ->assertFormFieldIsVisible('signed_at')
            ->assertFormFieldIsVisible('expires_at')
            ->assertFormFieldIsVisible('never_expires')
            ->call('create')
            ->assertHasFormErrors(['signed_at' => 'required',
                'expires_at' => 'required',
            ])
            ->fillForm(['never_expires' => true])
            ->call('create')
            ->assertHasFormErrors(['signed_at' => 'required'])
            ->fillForm(['signed_at' => $startDate,
                'expires_at' => fake()->date(max: $startDate),
                'never_expires' => false])
            ->call('create')
            ->assertHasFormErrors(['expires_at']);
    }
}

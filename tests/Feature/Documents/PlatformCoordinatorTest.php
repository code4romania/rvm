<?php

declare(strict_types=1);

namespace Tests\Feature\Documents;

use App\Enum\DocumentType;
use App\Filament\Resources\DocumentResource;
use App\Models\Document;
use App\Models\User;
use Livewire\Livewire;

class PlatformCoordinatorTest extends DocumentsBaseTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()
            ->platformCoordinator()
            ->create();

        Livewire::actingAs($this->user);

        $this->createOrganisations(3, 'random');
    }

    public function testPlatformCoordinatorCanViewDocuments(): void
    {
        $this->viewDocuments()
            ->assertPageActionHidden('create')
            ->assertPageActionDisabled('create');
    }

    public function testPlatformCoordinatorCanViewDocument(): void
    {
        $document = Document::query()
            ->whereType(DocumentType::protocol)
            ->inRandomOrder()
            ->first();

        $this->viewProtocolDocumentByUser($document)
            ->assertPageActionHidden('edit')
            ->assertPageActionHidden('delete')
            ->assertPageActionDisabled('edit')
            ->assertPageActionDisabled('delete');

        $document = Document::query()
            ->whereNot('type', DocumentType::protocol)
            ->inRandomOrder()
            ->first();

        $this->viewDocumentByUser($document)
            ->assertPageActionHidden('edit')
            ->assertPageActionHidden('delete')
            ->assertPageActionDisabled('edit')
            ->assertPageActionDisabled('delete');
    }

    public function testPlatformCoordinatorCanNotEditDocument(): void
    {
        $document = Document::query()
            ->inRandomOrder()
            ->first();

        Livewire::test(DocumentResource\Pages\EditDocument::class, ['record' => $document->id])
            ->assertForbidden();
    }

    public function testPlatformCoordinatorCanNotCreateDocument(): void
    {
        Livewire::test(DocumentResource\Pages\CreateDocument::class)
            ->assertForbidden();
    }
}

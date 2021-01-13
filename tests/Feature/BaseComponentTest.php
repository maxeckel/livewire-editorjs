<?php

namespace Maxeckel\LivewireEditorjs\Tests\Feature;

use ErrorException;
use Maxeckel\LivewireEditorjs\Tests\TestCase;

class BaseComponentTest extends TestCase
{
    /** @test */
    public function can_not_create_component_without_editor_id()
    {
        // ErrorException should be thrown because of an unresolvable dependency
        $this->expectException(ErrorException::class);

        $this->createComponent();
    }

    /** @test */
    public function can_create_component()
    {
        $component = $this->createComponent(['editorId' => 'testEditor']);

        $this->assertNotNull($component);
    }

    /** @test */
    public function id_is_set_to_passed_editor_id()
    {
        $component = $this->createComponent(['editorId' => 'testEditor']);

        $component->assertSeeHtml('id="testEditor"');
    }

    /** @test */
    public function alpine_component_gets_correct_parameters()
    {
        $component = $this->createComponent(['editorId' => 'testEditor']);

        $component->assertSeeHtml('x-data="editorInstance(\'data\', \'testEditor\', false, \'\', \'ERROR\')"');
    }

    /** @test */
    public function can_pass_class_to_component()
    {
        $component = $this->createComponent([
            'editorId' => 'testEditor',
            'class' => 'test-class',
        ]);

        $component->assertSeeHtml('class="test-class"');
    }

    /** @test */
    public function can_pass_style_to_component()
    {
        $component = $this->createComponent([
            'editorId' => 'testEditor',
            'style' => 'border-color: rgb(125, 125, 125)',
        ]);

        $component->assertSeeHtml('style="border-color: rgb(125, 125, 125)"');
    }

    /** @test */
    public function can_pass_upload_disk_to_component()
    {
        $component = $this->createComponent([
            'editorId' => 'testEditor',
            'uploadDisk' => 'dummy',
        ]);

        $component->assertSet('uploadDisk', 'dummy');
    }

    /** @test */
    public function can_pass_download_disk_to_component()
    {
        $component = $this->createComponent([
            'editorId' => 'testEditor',
            'downloadDisk' => 'dummy',
        ]);

        $component->assertSet('downloadDisk', 'dummy');
    }

    /** @test */
    public function can_pass_read_only_to_component()
    {
        $component = $this->createComponent([
            'editorId' => 'testEditor',
            'readOnly' => true,
        ]);

        $component->assertSet('readOnly', true);
    }

    /** @test */
    public function save_event_is_emitted_from_component_on_save()
    {
        $component = $this->createComponent(['editorId' => 'testEditor']);

        $component
            ->call('save')
            ->assertEmitted('editorjs-save:testEditor');
    }

    /** @test */
    public function correct_event_is_emitted_from_component_on_save()
    {
        $component = $this->createComponent(['editorId' => 'testEditor']);
        $anotherComponent = $this->createComponent(['editorId' => 'testEditor2']);

        $component
            ->call('save')
            ->assertEmitted('editorjs-save:testEditor', [])
            ->assertNotEmitted('editorjs-save:testEditor2');

        $anotherComponent
            ->call('save')
            ->assertEmitted('editorjs-save:testEditor2', [])
            ->assertNotEmitted('editorjs-save:testEditor');
    }

    /** @test */
    public function a_passed_string_as_value_gets_json_decoded_to_an_array()
    {
        $component = $this->createComponent([
            'editorId' => 'testEditor',
            'value' => '{"time": 1610484123806, "blocks": [{"data": {"text": "testing123"}, "type": "paragraph"}], "version": "2.19.1"}',
        ]);

        $this->assertTrue(is_array($component->get('data')));
    }

    /** @test */
    public function a_passed_in_array_as_value_is_stored_correctly()
    {
        $component = $this->createComponent([
            'editorId' => 'testEditor',
            'value' => json_decode('{"time": 1610484123806, "blocks": [{"data": {"text": "testing123"}, "type": "paragraph"}], "version": "2.19.1"}', true),
        ]);

        $this->assertTrue(is_array($component->get('data')));
    }
}

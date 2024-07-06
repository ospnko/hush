<?php

namespace Ospnko\Hush\Tests;

use Ospnko\Hush\Component\Breadcrumb\Breadcrumb;
use Ospnko\Hush\Component\Menu\Menu;
use Ospnko\Hush\Component\Table\Table;
use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\HushBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(HushBuilder::class)]
class HushBuilderTest extends TestCase
{
    public function testBlockAllParams(): void
    {
        $hush = new HushBuilder();

        $result = $hush->block(
            headline: 'Random headline',
            content: fn (HushBuilder $hush) => $hush->text('Greetings'),
            attributes: ['class' => 'some-class', 'id' => 'some-id'],
        );

        $expectedResult = <<<HTML
        <section class="some-class" id="some-id"><h1>Random headline</h1><p>Greetings</p></section>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testBlockSingleParam(): void
    {
        $hush = new HushBuilder();

        $result = $hush->block(
            content: fn (HushBuilder $hush) => $hush->text('Greetings'),
        );

        $expectedResult = <<<HTML
        <section><p>Greetings</p></section>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testBreadcrumbs(): void
    {
        $hush = new HushBuilder();

        $result = $hush->breadcrumbs(
            fn (Breadcrumb $builder) => $builder
                ->addItem('Home', '/')
                ->addItem('Products', '/products')
                ->addItem('Edit')
        );

        $expectedResult = <<<HTML
        <div class="breadcrumbs"><a href="/">Home</a><svg width="5" height="9" viewBox="0 0 5 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.666667 7.83335L4 4.50002L0.666667 1.16669" stroke="#8B8B8B" stroke-linecap="round" stroke-linejoin="round"/></svg><a href="/products">Products</a><svg width="5" height="9" viewBox="0 0 5 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.666667 7.83335L4 4.50002L0.666667 1.16669" stroke="#8B8B8B" stroke-linecap="round" stroke-linejoin="round"/></svg><p>Edit</p></div>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testButtonLinkAllParams(): void
    {
        $hush = new HushBuilder();

        $result = $hush->button(
            link: '/products',
            content: fn (HushBuilder $hush) => $hush->text('Some button'),
            isAsyncModal: true,
            attributes: ['class' => 'some-button', 'id' => 'some-id'],
        );

        $expectedResult = <<<HTML
        <a href="/products" class="async-modal-link button some-button" id="some-id"><p>Some button</p></a>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testButtonLinkLessParams(): void
    {
        $hush = new HushBuilder();

        $result = $hush->button(
            link: '/products',
            content: 'Some button',
        );

        $expectedResult = <<<HTML
        <a href="/products" class="button">Some button</a>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testButtonAllParams(): void
    {
        $hush = new HushBuilder();

        $result = $hush->button(
            content: fn (HushBuilder $hush) => $hush->text('Some button'),
            isAsyncModal: true,
            attributes: ['class' => 'some-class', 'id' => 'some-id'],
        );

        $expectedResult = <<<HTML
        <button class="async-modal-link some-class" id="some-id" data-is_form="1"><p>Some button</p></button>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testButtonSingleParam(): void
    {
        $hush = new HushBuilder();

        $result = $hush->button(
            content: 'Some button',
        );

        $expectedResult = <<<HTML
        <button data-is_form="1">Some button</button>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testCheckboxAllParams(): void
    {
        $hush = new HushBuilder();

        $result = $hush->checkbox(
            label: 'Some checkbox',
            name: 'checkbox-name',
            isChecked: true,
            value: 'some-value',
            attributes: ['class' => 'some-class', 'id' => 'some-id'],
        );

        $expectedResult = <<<HTML
        <label class="checkbox some-class" id="some-id"><input type="checkbox" name="checkbox-name" value="some-value" checked><div class="checked"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.2747 5.34176C16.5156 5.56945 16.6561 5.88345 16.6655 6.21474C16.6749 6.54604 16.5523 6.86749 16.3247 7.10843L9.24141 14.6084C9.12661 14.7298 8.98865 14.8269 8.8357 14.894C8.68274 14.9611 8.51788 14.9969 8.35086 14.9992C8.18383 15.0015 8.01804 14.9704 7.86327 14.9075C7.7085 14.8447 7.56789 14.7515 7.44974 14.6334L3.69974 10.8834C3.47894 10.6465 3.35874 10.3331 3.36445 10.0092C3.37017 9.68539 3.50135 9.37641 3.73037 9.14739C3.9594 8.91837 4.26837 8.78719 4.59221 8.78147C4.91604 8.77576 5.22945 8.89596 5.46641 9.11676L8.30808 11.9568L14.5081 5.39176C14.7358 5.15092 15.0498 5.01037 15.3811 5.00099C15.7123 4.99161 16.0338 5.11419 16.2747 5.34176Z" fill="#4700C2"/></svg></div><div class="unchecked"></div><p>Some checkbox</p></label>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testCheckboxSingleParam(): void
    {
        $hush = new HushBuilder();

        $result = $hush->checkbox(
            name: 'checkbox-name',
        );

        $expectedResult = <<<HTML
        <label class="checkbox "><input type="checkbox" name="checkbox-name" value="1"><div class="checked"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.2747 5.34176C16.5156 5.56945 16.6561 5.88345 16.6655 6.21474C16.6749 6.54604 16.5523 6.86749 16.3247 7.10843L9.24141 14.6084C9.12661 14.7298 8.98865 14.8269 8.8357 14.894C8.68274 14.9611 8.51788 14.9969 8.35086 14.9992C8.18383 15.0015 8.01804 14.9704 7.86327 14.9075C7.7085 14.8447 7.56789 14.7515 7.44974 14.6334L3.69974 10.8834C3.47894 10.6465 3.35874 10.3331 3.36445 10.0092C3.37017 9.68539 3.50135 9.37641 3.73037 9.14739C3.9594 8.91837 4.26837 8.78719 4.59221 8.78147C4.91604 8.77576 5.22945 8.89596 5.46641 9.11676L8.30808 11.9568L14.5081 5.39176C14.7358 5.15092 15.0498 5.01037 15.3811 5.00099C15.7123 4.99161 16.0338 5.11419 16.2747 5.34176Z" fill="#4700C2"/></svg></div><div class="unchecked"></div><p></p></label>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testErrors(): void
    {
        $hush = new HushBuilder();

        $result = $hush->errors(['Something went wrong', 'Another error']);

        $expectedResult = <<<HTML
        <small class="error">Something went wrong, Another error</small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testFlex(): void
    {
        $hush = new HushBuilder();

        $result = $hush->flex(
            attributes: ['class' => 'some-flex', 'id' => 'some-flex'],
            childAttributes: [
                'leftBlock' => ['class' => 'left-block'],
                'centerBlock' => ['id' => 'center-block'],
                'rightBlock' => [],
            ],
            leftBlock: fn (HushBuilder $hush) => $hush->text('Left block'),
            centerBlock: fn (HushBuilder $hush) => $hush->text('Center block'),
            rightBlock: fn (HushBuilder $hush) => $hush->text('Right block'),
        );

        $expectedResult = <<<HTML
        <div class="flex some-flex" id="some-flex"><div class="left-block"><p>Left block</p></div><div id="center-block"><p>Center block</p></div><div><p>Right block</p></div></div>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testForm(): void
    {
        $hush = new HushBuilder();

        $result = $hush->form(
            method: 'POST',
            action: '/products',
            csrfField: 'random-value',
            attributes: ['class' => 'some-form', 'id' => 'some-form'],
            content: fn (HushBuilder $hush) => $hush->input('random'),
        );

        $expectedResult = <<<HTML
        <form    action="/products"    method="POST"    class="some-form"id="some-form"><input type="hidden" name="_method" value="POST" />random-value<input type="text" name="random" value="" placeholder=""><small class="error"></small></form>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testHeading(): void
    {
        $hush = new HushBuilder();

        $result = $hush->heading(
            text: 'Some heading',
            attributes: ['class' => 'some-heading', 'id' => 'some-id'],
        );

        $expectedResult = <<<HTML
        <h1 class="some-heading" id="some-id">Some heading</h1>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testHtml(): void
    {
        $hush = new HushBuilder();

        $result = $hush->html('<div class="div"></div>');

        $expectedResult = <<<HTML
        <div class="div"></div>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    #[DataProvider('ifDataProvider')]
    public function testIf(bool $condition, string $expectedResult): void
    {
        $hush = new HushBuilder();

        $result = $hush->if(
            condition: $condition,
            content: fn (HushBuilder $hush) => $hush->text('Some text'),
        );

        $this->assertEquals($expectedResult, $result->render());
    }

    public static function ifDataProvider(): array
    {
        return [
            [true, '<p>Some text</p>'],
            [false, ''],
        ];
    }

    public function testImage(): void
    {
        $hush = new HushBuilder();

        $result = $hush->image(
            link: '/image.jpg',
            alt: 'Image',
            attributes: ['class' => 'some-image', 'id' => 'some-id'],
        );

        $expectedResult = <<<HTML
        <img src="/image.jpg" alt="Image" class="some-image" id="some-id">
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testInputAllParams(): void
    {
        $hush = new HushBuilder();

        $result = $hush->input(
            type: 'date',
            name: 'date-input',
            placeholder: 'Date input',
            value: '2000-01-01',
            isRequired: true,
            attributes: ['class' => 'some-input', 'id' => 'some-id'],
            errors: ['Some error', 'Another error'],
        );

        $expectedResult = <<<HTML
        <input type="date" name="date-input" value="2000-01-01" placeholder="Date input" class="has-error some-input" id="some-id" required=""><small class="error">Some error, Another error</small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testInputSingleParam(): void
    {
        $hush = new HushBuilder();

        $result = $hush->input(
            name: 'input',
        );

        $expectedResult = <<<HTML
        <input type="text" name="input" value="" placeholder=""><small class="error"></small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testInputFileAllParams(): void
    {
        $hush = new HushBuilder();

        $result = $hush->inputFile(
            name: 'some-name',
            value: 'some-value',
            placeholder: 'Some placeholder',
            asyncUrl: '/products',
            upperContent: fn (HushBuilder $hush) => $hush->text('Image here')->render(),
            isMultiple: true,
            errors: ['Some error', 'Another error'],
            attributes: ['class' => 'some-class', 'id' => 'some-id'],
            mainInputAttributes: ['class' => 'main-class', 'id' => 'main-id'],
        );

        $expectedResult = <<<HTML
        <div class="file some-class" id="some-id"><p>Image here</p><label class="input"><input type="hidden" name="some-name" value="some-value"><input            type="file"            name=""            data-async_url="/products"            formnovalidate            multiple            class="main-class" id="main-id">Some placeholder<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 11.878C9.008 12.272 8.61 12.781 8.348 13.365L8.344 13.375C8.266 13.548 8.201 13.693 8.148 13.804C8.09149 13.9296 8.02424 14.0501 7.947 14.164C7.80466 14.3722 7.62372 14.5511 7.414 14.691C7.20006 14.8249 6.96244 14.9166 6.714 14.961C6.514 15.001 6.272 15.001 6.064 15.001H6C5.46957 15.001 4.96086 15.2118 4.58579 15.5868C4.21071 15.9619 4 16.4706 4 17.001C4 17.5315 4.21071 18.0402 4.58579 18.4153C4.96086 18.7903 5.46957 19.001 6 19.001H18C18.5304 19.001 19.0391 18.7903 19.4142 18.4153C19.7893 18.0402 20 17.5315 20 17.001C20 16.4706 19.7893 15.9619 19.4142 15.5868C19.0391 15.2118 18.5304 15.001 18 15.001H17.936C17.728 15.001 17.486 15.001 17.286 14.961C17.0376 14.9166 16.7999 14.8249 16.586 14.691C16.3762 14.5508 16.1953 14.3715 16.053 14.163C15.9758 14.049 15.9085 13.9286 15.852 13.803C15.7852 13.661 15.7199 13.5184 15.656 13.375L15.652 13.365C15.3914 12.7847 14.9965 12.2747 14.5 11.877V9.54504C15.8221 10.1533 16.8793 11.2197 17.476 12.547C17.5434 12.6984 17.6124 12.8491 17.683 12.999H17.703C17.767 13.001 17.854 13.001 18 13.001C19.0609 13.001 20.0783 13.4225 20.8284 14.1726C21.5786 14.9228 22 15.9402 22 17.001C22 18.0619 21.5786 19.0793 20.8284 19.8295C20.0783 20.5796 19.0609 21.001 18 21.001H6C4.93913 21.001 3.92172 20.5796 3.17157 19.8295C2.42143 19.0793 2 18.0619 2 17.001C2 15.9402 2.42143 14.9228 3.17157 14.1726C3.92172 13.4225 4.93913 13.001 6 13.001C6.146 13.001 6.233 13.001 6.297 12.999H6.317C6.58915 12.2395 7.01115 11.5423 7.55793 10.949C8.10471 10.3556 8.76514 9.87822 9.5 9.54504V11.878Z" fill="#4700C2"/><path d="M12 3.00006L11.293 2.29306L12 1.58606L12.707 2.29306L12 3.00006ZM13 13.0001C13 13.2653 12.8946 13.5196 12.7071 13.7072C12.5195 13.8947 12.2652 14.0001 12 14.0001C11.7348 14.0001 11.4804 13.8947 11.2929 13.7072C11.1053 13.5196 11 13.2653 11 13.0001H13ZM7.29297 6.29306L11.293 2.29306L12.707 3.70706L8.70697 7.70706L7.29297 6.29306ZM12.707 2.29306L16.707 6.29306L15.293 7.70706L11.293 3.70706L12.707 2.29306ZM13 3.00006V13.0001H11V3.00006H13Z" fill="#4700C2"/></svg></label></div><small class="error">Some error, Another error</small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testInputFileLessParams(): void
    {
        $hush = new HushBuilder();

        $result = $hush->inputFile(
            name: 'some-name',
            value: 'some-value',
        );

        $expectedResult = <<<HTML
        <div class="file "><label class="input"><input            type="file"            name="some-name"            data-async_url=""><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 11.878C9.008 12.272 8.61 12.781 8.348 13.365L8.344 13.375C8.266 13.548 8.201 13.693 8.148 13.804C8.09149 13.9296 8.02424 14.0501 7.947 14.164C7.80466 14.3722 7.62372 14.5511 7.414 14.691C7.20006 14.8249 6.96244 14.9166 6.714 14.961C6.514 15.001 6.272 15.001 6.064 15.001H6C5.46957 15.001 4.96086 15.2118 4.58579 15.5868C4.21071 15.9619 4 16.4706 4 17.001C4 17.5315 4.21071 18.0402 4.58579 18.4153C4.96086 18.7903 5.46957 19.001 6 19.001H18C18.5304 19.001 19.0391 18.7903 19.4142 18.4153C19.7893 18.0402 20 17.5315 20 17.001C20 16.4706 19.7893 15.9619 19.4142 15.5868C19.0391 15.2118 18.5304 15.001 18 15.001H17.936C17.728 15.001 17.486 15.001 17.286 14.961C17.0376 14.9166 16.7999 14.8249 16.586 14.691C16.3762 14.5508 16.1953 14.3715 16.053 14.163C15.9758 14.049 15.9085 13.9286 15.852 13.803C15.7852 13.661 15.7199 13.5184 15.656 13.375L15.652 13.365C15.3914 12.7847 14.9965 12.2747 14.5 11.877V9.54504C15.8221 10.1533 16.8793 11.2197 17.476 12.547C17.5434 12.6984 17.6124 12.8491 17.683 12.999H17.703C17.767 13.001 17.854 13.001 18 13.001C19.0609 13.001 20.0783 13.4225 20.8284 14.1726C21.5786 14.9228 22 15.9402 22 17.001C22 18.0619 21.5786 19.0793 20.8284 19.8295C20.0783 20.5796 19.0609 21.001 18 21.001H6C4.93913 21.001 3.92172 20.5796 3.17157 19.8295C2.42143 19.0793 2 18.0619 2 17.001C2 15.9402 2.42143 14.9228 3.17157 14.1726C3.92172 13.4225 4.93913 13.001 6 13.001C6.146 13.001 6.233 13.001 6.297 12.999H6.317C6.58915 12.2395 7.01115 11.5423 7.55793 10.949C8.10471 10.3556 8.76514 9.87822 9.5 9.54504V11.878Z" fill="#4700C2"/><path d="M12 3.00006L11.293 2.29306L12 1.58606L12.707 2.29306L12 3.00006ZM13 13.0001C13 13.2653 12.8946 13.5196 12.7071 13.7072C12.5195 13.8947 12.2652 14.0001 12 14.0001C11.7348 14.0001 11.4804 13.8947 11.2929 13.7072C11.1053 13.5196 11 13.2653 11 13.0001H13ZM7.29297 6.29306L11.293 2.29306L12.707 3.70706L8.70697 7.70706L7.29297 6.29306ZM12.707 2.29306L16.707 6.29306L15.293 7.70706L11.293 3.70706L12.707 2.29306ZM13 3.00006V13.0001H11V3.00006H13Z" fill="#4700C2"/></svg></label></div><small class="error"></small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testInputImage(): void
    {
        $hush = new HushBuilder();

        $result = $hush->inputImage(
            name: 'some-name',
            image: 'some-image.jpg',
            placeholder: 'Some placeholder',
            asyncUrl: '/products',
            errors: ['Error #1'],
        );

        $expectedResult = <<<HTML
        <div class="file "><img src="some-image.jpg" alt=""><label class="input"><input type="hidden" name="some-name" value="some-image.jpg"><input            type="file"            name=""            data-async_url="/products"            formnovalidate>Some placeholder<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 11.878C9.008 12.272 8.61 12.781 8.348 13.365L8.344 13.375C8.266 13.548 8.201 13.693 8.148 13.804C8.09149 13.9296 8.02424 14.0501 7.947 14.164C7.80466 14.3722 7.62372 14.5511 7.414 14.691C7.20006 14.8249 6.96244 14.9166 6.714 14.961C6.514 15.001 6.272 15.001 6.064 15.001H6C5.46957 15.001 4.96086 15.2118 4.58579 15.5868C4.21071 15.9619 4 16.4706 4 17.001C4 17.5315 4.21071 18.0402 4.58579 18.4153C4.96086 18.7903 5.46957 19.001 6 19.001H18C18.5304 19.001 19.0391 18.7903 19.4142 18.4153C19.7893 18.0402 20 17.5315 20 17.001C20 16.4706 19.7893 15.9619 19.4142 15.5868C19.0391 15.2118 18.5304 15.001 18 15.001H17.936C17.728 15.001 17.486 15.001 17.286 14.961C17.0376 14.9166 16.7999 14.8249 16.586 14.691C16.3762 14.5508 16.1953 14.3715 16.053 14.163C15.9758 14.049 15.9085 13.9286 15.852 13.803C15.7852 13.661 15.7199 13.5184 15.656 13.375L15.652 13.365C15.3914 12.7847 14.9965 12.2747 14.5 11.877V9.54504C15.8221 10.1533 16.8793 11.2197 17.476 12.547C17.5434 12.6984 17.6124 12.8491 17.683 12.999H17.703C17.767 13.001 17.854 13.001 18 13.001C19.0609 13.001 20.0783 13.4225 20.8284 14.1726C21.5786 14.9228 22 15.9402 22 17.001C22 18.0619 21.5786 19.0793 20.8284 19.8295C20.0783 20.5796 19.0609 21.001 18 21.001H6C4.93913 21.001 3.92172 20.5796 3.17157 19.8295C2.42143 19.0793 2 18.0619 2 17.001C2 15.9402 2.42143 14.9228 3.17157 14.1726C3.92172 13.4225 4.93913 13.001 6 13.001C6.146 13.001 6.233 13.001 6.297 12.999H6.317C6.58915 12.2395 7.01115 11.5423 7.55793 10.949C8.10471 10.3556 8.76514 9.87822 9.5 9.54504V11.878Z" fill="#4700C2"/><path d="M12 3.00006L11.293 2.29306L12 1.58606L12.707 2.29306L12 3.00006ZM13 13.0001C13 13.2653 12.8946 13.5196 12.7071 13.7072C12.5195 13.8947 12.2652 14.0001 12 14.0001C11.7348 14.0001 11.4804 13.8947 11.2929 13.7072C11.1053 13.5196 11 13.2653 11 13.0001H13ZM7.29297 6.29306L11.293 2.29306L12.707 3.70706L8.70697 7.70706L7.29297 6.29306ZM12.707 2.29306L16.707 6.29306L15.293 7.70706L11.293 3.70706L12.707 2.29306ZM13 3.00006V13.0001H11V3.00006H13Z" fill="#4700C2"/></svg></label></div><small class="error">Error #1</small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testInputImageLessParams(): void
    {
        $hush = new HushBuilder();

        $result = $hush->inputImage(
            name: 'some-name',
        );

        $expectedResult = <<<HTML
        <div class="file "><img src="" alt=""><label class="input"><input            type="file"            name="some-name"            data-async_url=""><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 11.878C9.008 12.272 8.61 12.781 8.348 13.365L8.344 13.375C8.266 13.548 8.201 13.693 8.148 13.804C8.09149 13.9296 8.02424 14.0501 7.947 14.164C7.80466 14.3722 7.62372 14.5511 7.414 14.691C7.20006 14.8249 6.96244 14.9166 6.714 14.961C6.514 15.001 6.272 15.001 6.064 15.001H6C5.46957 15.001 4.96086 15.2118 4.58579 15.5868C4.21071 15.9619 4 16.4706 4 17.001C4 17.5315 4.21071 18.0402 4.58579 18.4153C4.96086 18.7903 5.46957 19.001 6 19.001H18C18.5304 19.001 19.0391 18.7903 19.4142 18.4153C19.7893 18.0402 20 17.5315 20 17.001C20 16.4706 19.7893 15.9619 19.4142 15.5868C19.0391 15.2118 18.5304 15.001 18 15.001H17.936C17.728 15.001 17.486 15.001 17.286 14.961C17.0376 14.9166 16.7999 14.8249 16.586 14.691C16.3762 14.5508 16.1953 14.3715 16.053 14.163C15.9758 14.049 15.9085 13.9286 15.852 13.803C15.7852 13.661 15.7199 13.5184 15.656 13.375L15.652 13.365C15.3914 12.7847 14.9965 12.2747 14.5 11.877V9.54504C15.8221 10.1533 16.8793 11.2197 17.476 12.547C17.5434 12.6984 17.6124 12.8491 17.683 12.999H17.703C17.767 13.001 17.854 13.001 18 13.001C19.0609 13.001 20.0783 13.4225 20.8284 14.1726C21.5786 14.9228 22 15.9402 22 17.001C22 18.0619 21.5786 19.0793 20.8284 19.8295C20.0783 20.5796 19.0609 21.001 18 21.001H6C4.93913 21.001 3.92172 20.5796 3.17157 19.8295C2.42143 19.0793 2 18.0619 2 17.001C2 15.9402 2.42143 14.9228 3.17157 14.1726C3.92172 13.4225 4.93913 13.001 6 13.001C6.146 13.001 6.233 13.001 6.297 12.999H6.317C6.58915 12.2395 7.01115 11.5423 7.55793 10.949C8.10471 10.3556 8.76514 9.87822 9.5 9.54504V11.878Z" fill="#4700C2"/><path d="M12 3.00006L11.293 2.29306L12 1.58606L12.707 2.29306L12 3.00006ZM13 13.0001C13 13.2653 12.8946 13.5196 12.7071 13.7072C12.5195 13.8947 12.2652 14.0001 12 14.0001C11.7348 14.0001 11.4804 13.8947 11.2929 13.7072C11.1053 13.5196 11 13.2653 11 13.0001H13ZM7.29297 6.29306L11.293 2.29306L12.707 3.70706L8.70697 7.70706L7.29297 6.29306ZM12.707 2.29306L16.707 6.29306L15.293 7.70706L11.293 3.70706L12.707 2.29306ZM13 3.00006V13.0001H11V3.00006H13Z" fill="#4700C2"/></svg></label></div><small class="error"></small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testInputImageMultiple(): void
    {
        $hush = new HushBuilder();

        $result = $hush->inputImageMultiple(
            name: 'some-name',
            images: ['some-image.jpg', 'some-image-1.jpg'],
            deleted: ['some-image.jpg'],
            placeholder: 'Some image',
            asyncUrl: '/products',
            isReorderingEnabled: true,
            errors: ['Error #1'],
        );

        $expectedResult = <<<HTML
        <div class="file multiple" data-name="some-name" data-template="&lt;div class=&quot;container&quot; style=&quot;order: {order}&quot;&gt;    &lt;div class=&quot;image-container&quot;&gt;        &lt;img src=&quot;{image}&quot; alt=&quot;&quot;&gt;    &lt;/div&gt;    &lt;div class=&quot;controls&quot;&gt;        &lt;a class=&quot;delete-image&quot;&gt;&lt;svg width=&quot;24&quot; height=&quot;24&quot; viewBox=&quot;0 0 24 24&quot; fill=&quot;none&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;&gt;    &lt;path d=&quot;M12 2C6.47625 2 2 6.4775 2 12C2 17.5225 6.47625 22 12 22C17.5238 22 22 17.5225 22 12C22 6.4775 17.5238 2 12 2ZM16.6337 14.8663C16.8681 15.1006 16.9998 15.4185 16.9998 15.75C16.9998 16.0815 16.8681 16.3994 16.6337 16.6337C16.3994 16.8681 16.0815 16.9998 15.75 16.9998C15.4185 16.9998 15.1006 16.8681 14.8663 16.6337L12 13.7675L9.13375 16.6337C9.01793 16.7502 8.88023 16.8427 8.72857 16.9057C8.5769 16.9688 8.41426 17.0013 8.25 17.0013C8.08574 17.0013 7.9231 16.9688 7.77143 16.9057C7.61977 16.8427 7.48207 16.7502 7.36625 16.6337C7.25007 16.5178 7.1579 16.38 7.09501 16.2284C7.03212 16.0767 6.99975 15.9142 6.99975 15.75C6.99975 15.5858 7.03212 15.4233 7.09501 15.2716C7.1579 15.12 7.25007 14.9822 7.36625 14.8663L10.2325 12L7.36625 9.13375C7.13187 8.89936 7.00019 8.58147 7.00019 8.25C7.00019 7.91853 7.13187 7.60064 7.36625 7.36625C7.60064 7.13187 7.91853 7.00019 8.25 7.00019C8.58147 7.00019 8.89936 7.13187 9.13375 7.36625L12 10.2325L14.8663 7.36625C15.1006 7.13187 15.4185 7.00019 15.75 7.00019C16.0815 7.00019 16.3994 7.13187 16.6337 7.36625C16.8681 7.60064 16.9998 7.91853 16.9998 8.25C16.9998 8.58147 16.8681 8.89936 16.6337 9.13375L13.7675 12L16.6337 14.8663Z&quot; fill=&quot;#EE4392&quot;/&gt;&lt;/svg&gt;&lt;/a&gt;&lt;a class=&quot;move-image-left&quot;&gt;&lt;svg width=&quot;12&quot; height=&quot;7&quot; viewBox=&quot;0 0 12 7&quot; fill=&quot;none&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;&gt;    &lt;path d=&quot;M1 1L6 6L11 1&quot; stroke=&quot;#4700C2&quot; stroke-width=&quot;1.5&quot; stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot;/&gt;&lt;/svg&gt;&lt;/a&gt;&lt;a class=&quot;move-image-right&quot;&gt;&lt;svg width=&quot;12&quot; height=&quot;7&quot; viewBox=&quot;0 0 12 7&quot; fill=&quot;none&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;&gt;    &lt;path d=&quot;M1 1L6 6L11 1&quot; stroke=&quot;#4700C2&quot; stroke-width=&quot;1.5&quot; stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot;/&gt;&lt;/svg&gt;&lt;/a&gt;    &lt;/div&gt;&lt;/div&gt;" data-value="{&quot;order&quot;:{&quot;some-image.jpg&quot;:0,&quot;some-image-1.jpg&quot;:1},&quot;deleted&quot;:{&quot;some-image.jpg&quot;:true}}"><div class="images"><div class="container removed" style="order: 0"><div class="image-container"><img src="some-image.jpg" alt=""></div><div class="controls"><a class="delete-image"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.47625 2 2 6.4775 2 12C2 17.5225 6.47625 22 12 22C17.5238 22 22 17.5225 22 12C22 6.4775 17.5238 2 12 2ZM16.6337 14.8663C16.8681 15.1006 16.9998 15.4185 16.9998 15.75C16.9998 16.0815 16.8681 16.3994 16.6337 16.6337C16.3994 16.8681 16.0815 16.9998 15.75 16.9998C15.4185 16.9998 15.1006 16.8681 14.8663 16.6337L12 13.7675L9.13375 16.6337C9.01793 16.7502 8.88023 16.8427 8.72857 16.9057C8.5769 16.9688 8.41426 17.0013 8.25 17.0013C8.08574 17.0013 7.9231 16.9688 7.77143 16.9057C7.61977 16.8427 7.48207 16.7502 7.36625 16.6337C7.25007 16.5178 7.1579 16.38 7.09501 16.2284C7.03212 16.0767 6.99975 15.9142 6.99975 15.75C6.99975 15.5858 7.03212 15.4233 7.09501 15.2716C7.1579 15.12 7.25007 14.9822 7.36625 14.8663L10.2325 12L7.36625 9.13375C7.13187 8.89936 7.00019 8.58147 7.00019 8.25C7.00019 7.91853 7.13187 7.60064 7.36625 7.36625C7.60064 7.13187 7.91853 7.00019 8.25 7.00019C8.58147 7.00019 8.89936 7.13187 9.13375 7.36625L12 10.2325L14.8663 7.36625C15.1006 7.13187 15.4185 7.00019 15.75 7.00019C16.0815 7.00019 16.3994 7.13187 16.6337 7.36625C16.8681 7.60064 16.9998 7.91853 16.9998 8.25C16.9998 8.58147 16.8681 8.89936 16.6337 9.13375L13.7675 12L16.6337 14.8663Z" fill="#EE4392"/></svg></a><a class="move-image-left"><svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 6L11 1" stroke="#4700C2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a><a class="move-image-right"><svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 6L11 1" stroke="#4700C2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a></div></div><div class="container" style="order: 1"><div class="image-container"><img src="some-image-1.jpg" alt=""></div><div class="controls"><a class="delete-image"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.47625 2 2 6.4775 2 12C2 17.5225 6.47625 22 12 22C17.5238 22 22 17.5225 22 12C22 6.4775 17.5238 2 12 2ZM16.6337 14.8663C16.8681 15.1006 16.9998 15.4185 16.9998 15.75C16.9998 16.0815 16.8681 16.3994 16.6337 16.6337C16.3994 16.8681 16.0815 16.9998 15.75 16.9998C15.4185 16.9998 15.1006 16.8681 14.8663 16.6337L12 13.7675L9.13375 16.6337C9.01793 16.7502 8.88023 16.8427 8.72857 16.9057C8.5769 16.9688 8.41426 17.0013 8.25 17.0013C8.08574 17.0013 7.9231 16.9688 7.77143 16.9057C7.61977 16.8427 7.48207 16.7502 7.36625 16.6337C7.25007 16.5178 7.1579 16.38 7.09501 16.2284C7.03212 16.0767 6.99975 15.9142 6.99975 15.75C6.99975 15.5858 7.03212 15.4233 7.09501 15.2716C7.1579 15.12 7.25007 14.9822 7.36625 14.8663L10.2325 12L7.36625 9.13375C7.13187 8.89936 7.00019 8.58147 7.00019 8.25C7.00019 7.91853 7.13187 7.60064 7.36625 7.36625C7.60064 7.13187 7.91853 7.00019 8.25 7.00019C8.58147 7.00019 8.89936 7.13187 9.13375 7.36625L12 10.2325L14.8663 7.36625C15.1006 7.13187 15.4185 7.00019 15.75 7.00019C16.0815 7.00019 16.3994 7.13187 16.6337 7.36625C16.8681 7.60064 16.9998 7.91853 16.9998 8.25C16.9998 8.58147 16.8681 8.89936 16.6337 9.13375L13.7675 12L16.6337 14.8663Z" fill="#EE4392"/></svg></a><a class="move-image-left"><svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 6L11 1" stroke="#4700C2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a><a class="move-image-right"><svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 6L11 1" stroke="#4700C2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a></div></div></div><label class="input"><input type="hidden" name="some-name[images][1]" value="some-image-1.jpg"><input type="hidden" name="some-name[order][0]" value="some-image.jpg"><input type="hidden" name="some-name[order][1]" value="some-image-1.jpg"><input type="hidden" name="some-name[deleted][0]" value="some-image.jpg"><input            type="file"            name=""            data-async_url="/products"            formnovalidate            multiple>Some image<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 11.878C9.008 12.272 8.61 12.781 8.348 13.365L8.344 13.375C8.266 13.548 8.201 13.693 8.148 13.804C8.09149 13.9296 8.02424 14.0501 7.947 14.164C7.80466 14.3722 7.62372 14.5511 7.414 14.691C7.20006 14.8249 6.96244 14.9166 6.714 14.961C6.514 15.001 6.272 15.001 6.064 15.001H6C5.46957 15.001 4.96086 15.2118 4.58579 15.5868C4.21071 15.9619 4 16.4706 4 17.001C4 17.5315 4.21071 18.0402 4.58579 18.4153C4.96086 18.7903 5.46957 19.001 6 19.001H18C18.5304 19.001 19.0391 18.7903 19.4142 18.4153C19.7893 18.0402 20 17.5315 20 17.001C20 16.4706 19.7893 15.9619 19.4142 15.5868C19.0391 15.2118 18.5304 15.001 18 15.001H17.936C17.728 15.001 17.486 15.001 17.286 14.961C17.0376 14.9166 16.7999 14.8249 16.586 14.691C16.3762 14.5508 16.1953 14.3715 16.053 14.163C15.9758 14.049 15.9085 13.9286 15.852 13.803C15.7852 13.661 15.7199 13.5184 15.656 13.375L15.652 13.365C15.3914 12.7847 14.9965 12.2747 14.5 11.877V9.54504C15.8221 10.1533 16.8793 11.2197 17.476 12.547C17.5434 12.6984 17.6124 12.8491 17.683 12.999H17.703C17.767 13.001 17.854 13.001 18 13.001C19.0609 13.001 20.0783 13.4225 20.8284 14.1726C21.5786 14.9228 22 15.9402 22 17.001C22 18.0619 21.5786 19.0793 20.8284 19.8295C20.0783 20.5796 19.0609 21.001 18 21.001H6C4.93913 21.001 3.92172 20.5796 3.17157 19.8295C2.42143 19.0793 2 18.0619 2 17.001C2 15.9402 2.42143 14.9228 3.17157 14.1726C3.92172 13.4225 4.93913 13.001 6 13.001C6.146 13.001 6.233 13.001 6.297 12.999H6.317C6.58915 12.2395 7.01115 11.5423 7.55793 10.949C8.10471 10.3556 8.76514 9.87822 9.5 9.54504V11.878Z" fill="#4700C2"/><path d="M12 3.00006L11.293 2.29306L12 1.58606L12.707 2.29306L12 3.00006ZM13 13.0001C13 13.2653 12.8946 13.5196 12.7071 13.7072C12.5195 13.8947 12.2652 14.0001 12 14.0001C11.7348 14.0001 11.4804 13.8947 11.2929 13.7072C11.1053 13.5196 11 13.2653 11 13.0001H13ZM7.29297 6.29306L11.293 2.29306L12.707 3.70706L8.70697 7.70706L7.29297 6.29306ZM12.707 2.29306L16.707 6.29306L15.293 7.70706L11.293 3.70706L12.707 2.29306ZM13 3.00006V13.0001H11V3.00006H13Z" fill="#4700C2"/></svg></label></div><small class="error">Error #1</small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testInputImageMultipleLessParams(): void
    {
        $hush = new HushBuilder();

        $result = $hush->inputImageMultiple(
            name: 'some-name',
        );

        $expectedResult = <<<HTML
        <div class="file multiple" data-name="some-name" data-template="&lt;div class=&quot;container&quot; style=&quot;order: {order}&quot;&gt;    &lt;div class=&quot;image-container&quot;&gt;        &lt;img src=&quot;{image}&quot; alt=&quot;&quot;&gt;    &lt;/div&gt;    &lt;div class=&quot;controls&quot;&gt;            &lt;/div&gt;&lt;/div&gt;" data-value="{&quot;order&quot;:{},&quot;deleted&quot;:{}}"><div class="images"></div><label class="input"><input            type="file"            name="some-name"            data-async_url=""                        multiple><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 11.878C9.008 12.272 8.61 12.781 8.348 13.365L8.344 13.375C8.266 13.548 8.201 13.693 8.148 13.804C8.09149 13.9296 8.02424 14.0501 7.947 14.164C7.80466 14.3722 7.62372 14.5511 7.414 14.691C7.20006 14.8249 6.96244 14.9166 6.714 14.961C6.514 15.001 6.272 15.001 6.064 15.001H6C5.46957 15.001 4.96086 15.2118 4.58579 15.5868C4.21071 15.9619 4 16.4706 4 17.001C4 17.5315 4.21071 18.0402 4.58579 18.4153C4.96086 18.7903 5.46957 19.001 6 19.001H18C18.5304 19.001 19.0391 18.7903 19.4142 18.4153C19.7893 18.0402 20 17.5315 20 17.001C20 16.4706 19.7893 15.9619 19.4142 15.5868C19.0391 15.2118 18.5304 15.001 18 15.001H17.936C17.728 15.001 17.486 15.001 17.286 14.961C17.0376 14.9166 16.7999 14.8249 16.586 14.691C16.3762 14.5508 16.1953 14.3715 16.053 14.163C15.9758 14.049 15.9085 13.9286 15.852 13.803C15.7852 13.661 15.7199 13.5184 15.656 13.375L15.652 13.365C15.3914 12.7847 14.9965 12.2747 14.5 11.877V9.54504C15.8221 10.1533 16.8793 11.2197 17.476 12.547C17.5434 12.6984 17.6124 12.8491 17.683 12.999H17.703C17.767 13.001 17.854 13.001 18 13.001C19.0609 13.001 20.0783 13.4225 20.8284 14.1726C21.5786 14.9228 22 15.9402 22 17.001C22 18.0619 21.5786 19.0793 20.8284 19.8295C20.0783 20.5796 19.0609 21.001 18 21.001H6C4.93913 21.001 3.92172 20.5796 3.17157 19.8295C2.42143 19.0793 2 18.0619 2 17.001C2 15.9402 2.42143 14.9228 3.17157 14.1726C3.92172 13.4225 4.93913 13.001 6 13.001C6.146 13.001 6.233 13.001 6.297 12.999H6.317C6.58915 12.2395 7.01115 11.5423 7.55793 10.949C8.10471 10.3556 8.76514 9.87822 9.5 9.54504V11.878Z" fill="#4700C2"/><path d="M12 3.00006L11.293 2.29306L12 1.58606L12.707 2.29306L12 3.00006ZM13 13.0001C13 13.2653 12.8946 13.5196 12.7071 13.7072C12.5195 13.8947 12.2652 14.0001 12 14.0001C11.7348 14.0001 11.4804 13.8947 11.2929 13.7072C11.1053 13.5196 11 13.2653 11 13.0001H13ZM7.29297 6.29306L11.293 2.29306L12.707 3.70706L8.70697 7.70706L7.29297 6.29306ZM12.707 2.29306L16.707 6.29306L15.293 7.70706L11.293 3.70706L12.707 2.29306ZM13 3.00006V13.0001H11V3.00006H13Z" fill="#4700C2"/></svg></label></div><small class="error"></small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testInputMediaVideo(): void
    {
        $hush = new HushBuilder();

        $result = $hush->inputMedia(
            name: 'some-name',
            media: 'some-video.mp4',
            isVideo: true,
            placeholder: 'Some placeholder',
            asyncUrl: '/products',
            errors: ['Error #1'],
        );

        $expectedResult = <<<HTML
        <div class="file "><img src="some-video.mp4" alt="" style="display: none"><video src="some-video.mp4"></video><label class="input"><input type="hidden" name="some-name" value="some-video.mp4"><input            type="file"            name=""            data-async_url="/products"            formnovalidate                        data-is_video_available="1">Some placeholder<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 11.878C9.008 12.272 8.61 12.781 8.348 13.365L8.344 13.375C8.266 13.548 8.201 13.693 8.148 13.804C8.09149 13.9296 8.02424 14.0501 7.947 14.164C7.80466 14.3722 7.62372 14.5511 7.414 14.691C7.20006 14.8249 6.96244 14.9166 6.714 14.961C6.514 15.001 6.272 15.001 6.064 15.001H6C5.46957 15.001 4.96086 15.2118 4.58579 15.5868C4.21071 15.9619 4 16.4706 4 17.001C4 17.5315 4.21071 18.0402 4.58579 18.4153C4.96086 18.7903 5.46957 19.001 6 19.001H18C18.5304 19.001 19.0391 18.7903 19.4142 18.4153C19.7893 18.0402 20 17.5315 20 17.001C20 16.4706 19.7893 15.9619 19.4142 15.5868C19.0391 15.2118 18.5304 15.001 18 15.001H17.936C17.728 15.001 17.486 15.001 17.286 14.961C17.0376 14.9166 16.7999 14.8249 16.586 14.691C16.3762 14.5508 16.1953 14.3715 16.053 14.163C15.9758 14.049 15.9085 13.9286 15.852 13.803C15.7852 13.661 15.7199 13.5184 15.656 13.375L15.652 13.365C15.3914 12.7847 14.9965 12.2747 14.5 11.877V9.54504C15.8221 10.1533 16.8793 11.2197 17.476 12.547C17.5434 12.6984 17.6124 12.8491 17.683 12.999H17.703C17.767 13.001 17.854 13.001 18 13.001C19.0609 13.001 20.0783 13.4225 20.8284 14.1726C21.5786 14.9228 22 15.9402 22 17.001C22 18.0619 21.5786 19.0793 20.8284 19.8295C20.0783 20.5796 19.0609 21.001 18 21.001H6C4.93913 21.001 3.92172 20.5796 3.17157 19.8295C2.42143 19.0793 2 18.0619 2 17.001C2 15.9402 2.42143 14.9228 3.17157 14.1726C3.92172 13.4225 4.93913 13.001 6 13.001C6.146 13.001 6.233 13.001 6.297 12.999H6.317C6.58915 12.2395 7.01115 11.5423 7.55793 10.949C8.10471 10.3556 8.76514 9.87822 9.5 9.54504V11.878Z" fill="#4700C2"/><path d="M12 3.00006L11.293 2.29306L12 1.58606L12.707 2.29306L12 3.00006ZM13 13.0001C13 13.2653 12.8946 13.5196 12.7071 13.7072C12.5195 13.8947 12.2652 14.0001 12 14.0001C11.7348 14.0001 11.4804 13.8947 11.2929 13.7072C11.1053 13.5196 11 13.2653 11 13.0001H13ZM7.29297 6.29306L11.293 2.29306L12.707 3.70706L8.70697 7.70706L7.29297 6.29306ZM12.707 2.29306L16.707 6.29306L15.293 7.70706L11.293 3.70706L12.707 2.29306ZM13 3.00006V13.0001H11V3.00006H13Z" fill="#4700C2"/></svg></label></div><small class="error">Error #1</small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testInputMediaImage(): void
    {
        $hush = new HushBuilder();

        $result = $hush->inputMedia(
            name: 'some-name',
            media: 'some-image.jpg',
            isVideo: false,
            placeholder: 'Some placeholder',
            asyncUrl: '/products',
            errors: ['Error #1'],
        );

        $expectedResult = <<<HTML
        <div class="file "><img src="some-image.jpg" alt=""><video src="some-image.jpg" style="display: none"></video><label class="input"><input type="hidden" name="some-name" value="some-image.jpg"><input            type="file"            name=""            data-async_url="/products"            formnovalidate                        data-is_video_available="1">Some placeholder<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 11.878C9.008 12.272 8.61 12.781 8.348 13.365L8.344 13.375C8.266 13.548 8.201 13.693 8.148 13.804C8.09149 13.9296 8.02424 14.0501 7.947 14.164C7.80466 14.3722 7.62372 14.5511 7.414 14.691C7.20006 14.8249 6.96244 14.9166 6.714 14.961C6.514 15.001 6.272 15.001 6.064 15.001H6C5.46957 15.001 4.96086 15.2118 4.58579 15.5868C4.21071 15.9619 4 16.4706 4 17.001C4 17.5315 4.21071 18.0402 4.58579 18.4153C4.96086 18.7903 5.46957 19.001 6 19.001H18C18.5304 19.001 19.0391 18.7903 19.4142 18.4153C19.7893 18.0402 20 17.5315 20 17.001C20 16.4706 19.7893 15.9619 19.4142 15.5868C19.0391 15.2118 18.5304 15.001 18 15.001H17.936C17.728 15.001 17.486 15.001 17.286 14.961C17.0376 14.9166 16.7999 14.8249 16.586 14.691C16.3762 14.5508 16.1953 14.3715 16.053 14.163C15.9758 14.049 15.9085 13.9286 15.852 13.803C15.7852 13.661 15.7199 13.5184 15.656 13.375L15.652 13.365C15.3914 12.7847 14.9965 12.2747 14.5 11.877V9.54504C15.8221 10.1533 16.8793 11.2197 17.476 12.547C17.5434 12.6984 17.6124 12.8491 17.683 12.999H17.703C17.767 13.001 17.854 13.001 18 13.001C19.0609 13.001 20.0783 13.4225 20.8284 14.1726C21.5786 14.9228 22 15.9402 22 17.001C22 18.0619 21.5786 19.0793 20.8284 19.8295C20.0783 20.5796 19.0609 21.001 18 21.001H6C4.93913 21.001 3.92172 20.5796 3.17157 19.8295C2.42143 19.0793 2 18.0619 2 17.001C2 15.9402 2.42143 14.9228 3.17157 14.1726C3.92172 13.4225 4.93913 13.001 6 13.001C6.146 13.001 6.233 13.001 6.297 12.999H6.317C6.58915 12.2395 7.01115 11.5423 7.55793 10.949C8.10471 10.3556 8.76514 9.87822 9.5 9.54504V11.878Z" fill="#4700C2"/><path d="M12 3.00006L11.293 2.29306L12 1.58606L12.707 2.29306L12 3.00006ZM13 13.0001C13 13.2653 12.8946 13.5196 12.7071 13.7072C12.5195 13.8947 12.2652 14.0001 12 14.0001C11.7348 14.0001 11.4804 13.8947 11.2929 13.7072C11.1053 13.5196 11 13.2653 11 13.0001H13ZM7.29297 6.29306L11.293 2.29306L12.707 3.70706L8.70697 7.70706L7.29297 6.29306ZM12.707 2.29306L16.707 6.29306L15.293 7.70706L11.293 3.70706L12.707 2.29306ZM13 3.00006V13.0001H11V3.00006H13Z" fill="#4700C2"/></svg></label></div><small class="error">Error #1</small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testInputSearch(): void
    {
        $hush = new HushBuilder();

        $result = $hush->inputSearch(
            name: 'some-name',
            placeholder: 'Some placeholder',
            value: 'Something',
            attributes: ['class' => 'some-class'],
        );

        $expectedResult = <<<HTML
        <div class="search-input margin-bottom-0"><div class="search"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.0961 5.90395C14.4946 5.29126 13.7776 4.80382 12.9866 4.46978C12.1956 4.13574 11.3463 3.96172 10.4877 3.95778C9.62911 3.95383 8.77823 4.12004 7.98421 4.44679C7.1902 4.77355 6.46879 5.25438 5.86166 5.86152C5.25452 6.46865 4.77369 7.19006 4.44694 7.98407C4.12018 8.77809 3.95398 9.62897 3.95792 10.4876C3.96187 11.3462 4.13588 12.1955 4.46992 12.9865C4.80396 13.7775 5.2914 14.4944 5.90409 15.0959C7.12684 16.2964 8.77418 16.9655 10.4877 16.9576C12.2013 16.9498 13.8424 16.2656 15.054 15.0539C16.2657 13.8422 16.9499 12.2011 16.9578 10.4876C16.9657 8.77404 16.2966 7.1267 15.0961 5.90395ZM4.49009 4.48995C6.02291 2.95751 8.08324 2.06852 10.2498 2.00478C12.4163 1.94103 14.5253 2.70735 16.1456 4.14702C17.7659 5.58669 18.7749 7.59094 18.9665 9.74992C19.158 11.9089 18.5176 14.0595 17.1761 15.7619L22.5211 21.1069L21.1071 22.5209L15.7621 17.1759C14.0591 18.5125 11.9103 19.149 9.75415 18.9555C7.59796 18.7621 5.5968 17.7534 4.15887 16.1351C2.72094 14.5167 1.95459 12.4108 2.01614 10.2468C2.07769 8.08288 2.9625 6.02391 4.49009 4.48995Z" fill="#999999"/></svg></div><input type="text" name="some-name" value="Something" placeholder="Some placeholder" class="some-class"><small class="error"></small><div class="clear"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.47625 2 2 6.4775 2 12C2 17.5225 6.47625 22 12 22C17.5238 22 22 17.5225 22 12C22 6.4775 17.5238 2 12 2ZM16.6337 14.8663C16.8681 15.1006 16.9998 15.4185 16.9998 15.75C16.9998 16.0815 16.8681 16.3994 16.6337 16.6337C16.3994 16.8681 16.0815 16.9998 15.75 16.9998C15.4185 16.9998 15.1006 16.8681 14.8663 16.6337L12 13.7675L9.13375 16.6337C9.01793 16.7502 8.88023 16.8427 8.72857 16.9057C8.5769 16.9688 8.41426 17.0013 8.25 17.0013C8.08574 17.0013 7.9231 16.9688 7.77143 16.9057C7.61977 16.8427 7.48207 16.7502 7.36625 16.6337C7.25007 16.5178 7.1579 16.38 7.09501 16.2284C7.03212 16.0767 6.99975 15.9142 6.99975 15.75C6.99975 15.5858 7.03212 15.4233 7.09501 15.2716C7.1579 15.12 7.25007 14.9822 7.36625 14.8663L10.2325 12L7.36625 9.13375C7.13187 8.89936 7.00019 8.58147 7.00019 8.25C7.00019 7.91853 7.13187 7.60064 7.36625 7.36625C7.60064 7.13187 7.91853 7.00019 8.25 7.00019C8.58147 7.00019 8.89936 7.13187 9.13375 7.36625L12 10.2325L14.8663 7.36625C15.1006 7.13187 15.4185 7.00019 15.75 7.00019C16.0815 7.00019 16.3994 7.13187 16.6337 7.36625C16.8681 7.60064 16.9998 7.91853 16.9998 8.25C16.9998 8.58147 16.8681 8.89936 16.6337 9.13375L13.7675 12L16.6337 14.8663Z" fill="#999999"/></svg></div></div>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testLabel(): void
    {
        $hush = new HushBuilder();

        $result = $hush->label(
            content: 'Something',
            attributes: ['class' => 'some-class'],
        );

        $expectedResult = <<<HTML
        <label class="some-class">Something</label>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testLayout(): void
    {
        $hush = new HushBuilder();

        $result = $hush->layout(
            csrfToken: '...',
            cssPath: '/asset/css/app.css',
            cssColorsPath: '/asset/css/light.css',
            faviconPath: '/favicon.png',
            faviconType: 'image/png',
            fontsPath: '/asset/fonts',
            jsPath: '/asset/js',
            locale: 'en',
            title: 'Page title',
        );

        $expectedResult = <<<HTML
        <!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=1280px, initial-scale=1.0"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta name="csrf-token" content="..."><title>Page title</title><link rel="icon" type="image/png" href="/favicon.png"><style>@font-face {    font-family: 'Inter';    font-style:  normal;    font-weight: 100;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-Thin.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-Thin.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  italic;    font-weight: 100;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-ThinItalic.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-ThinItalic.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  normal;    font-weight: 200;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-ExtraLight.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-ExtraLight.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  italic;    font-weight: 200;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-ExtraLightItalic.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-ExtraLightItalic.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  normal;    font-weight: 300;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-Light.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-Light.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  italic;    font-weight: 300;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-LightItalic.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-LightItalic.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  normal;    font-weight: 400;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-Regular.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-Regular.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  italic;    font-weight: 400;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-Italic.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-Italic.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  normal;    font-weight: 500;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-Medium.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-Medium.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  italic;    font-weight: 500;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-MediumItalic.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-MediumItalic.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  normal;    font-weight: 600;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-SemiBold.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-SemiBold.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  italic;    font-weight: 600;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-SemiBoldItalic.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-SemiBoldItalic.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  normal;    font-weight: 700;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-Bold.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-Bold.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  italic;    font-weight: 700;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-BoldItalic.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-BoldItalic.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  normal;    font-weight: 800;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-ExtraBold.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-ExtraBold.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  italic;    font-weight: 800;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-ExtraBoldItalic.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-ExtraBoldItalic.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  normal;    font-weight: 900;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-Black.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-Black.woff?v=3.12") format("woff");}@font-face {    font-family: 'Inter';    font-style:  italic;    font-weight: 900;    font-display: swap;    src: url("/asset/fonts/Inter/Inter-BlackItalic.woff2?v=3.12") format("woff2"),        url("/asset/fonts/Inter/Inter-BlackItalic.woff?v=3.12") format("woff");}</style><link href="/asset/css/light.css" rel="stylesheet"><link href="/asset/css/app.css" rel="stylesheet"></head><body><div></div><div class="modal-container"><div></div></div><script src="/asset/js/app.js"></script></body></html>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testLink(): void
    {
        $hush = new HushBuilder();

        $result = $hush->link(
            link: '/products',
            content: 'Content',
            isAsyncModal: true,
            attributes: ['class' => 'some-class'],
        );

        $expectedResult = <<<HTML
        <a href="/products" class="async-modal-link some-class">Content</a>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testLinkClosure(): void
    {
        $hush = new HushBuilder();

        $result = $hush->link(
            link: '/products',
            content: fn (HushBuilder $hush) => $hush->text('Content'),
            isAsyncModal: true,
            attributes: ['class' => 'some-class'],
        );

        $expectedResult = <<<HTML
        <a href="/products" class="async-modal-link some-class"><p>Content</p></a>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testLoop(): void
    {
        $hush = new HushBuilder();

        $result = $hush->loop(
            rows: [1, 2, 3],
            content: fn (HushBuilder $hush) => $hush->text('Content'),
            attributes: ['class' => 'some-class'],
        );

        $expectedResult = <<<HTML
        <div class="some-class"><p>Content</p><p>Content</p><p>Content</p></div>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testMenu(): void
    {
        $hush = new HushBuilder();

        $result = $hush->menu(
            content: fn (Menu $menu) => $menu
                ->addItem(text: 'Item #1', link: '/products', isActive: true)
                ->addItem(
                    text: 'Item #2',
                    link: '/products/edit',
                    isActive: false,
                    attributes: ['class' => 'some-class'],
                    submenu: fn (Menu $menu) => $menu
                        ->addItem(text: 'Item #1', link: '/products', isActive: true)
                ),
            attributes: ['class' => 'some-class'],
        );

        $expectedResult = <<<HTML
        <menu class="some-class"><li><a class="active">Item #1</a></li><li class="has-submenu some-class"><a href="/products/edit">Item #2<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 6L11 1" stroke="#4700C2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a><ul class="submenu "><li><a class="active">Item #1</a></li></ul></li></menu>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testModal(): void
    {
        $hush = new HushBuilder();

        $result = $hush->modal(
            title: 'Something',
            isHeadingShown: true,
            content: fn (HushBuilder $hush) => $hush
                ->text('Something'),
            footer: fn (HushBuilder $hush) => $hush
                ->button('Submit'),
        );

        $expectedResult = <<<HTML
        <div class="modal"><div class="heading"><h1>Something</h1><a class="modal-close"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.9996 10.122L13.3026 15.425C13.584 15.7064 13.9657 15.8645 14.3636 15.8645C14.7616 15.8645 15.1432 15.7064 15.4246 15.425C15.706 15.1436 15.8641 14.7619 15.8641 14.364C15.8641 13.966 15.706 13.5844 15.4246 13.303L10.1196 7.99999L15.4236 2.69699C15.5629 2.55766 15.6733 2.39226 15.7487 2.21024C15.824 2.02821 15.8628 1.83313 15.8627 1.63613C15.8627 1.43914 15.8238 1.24407 15.7484 1.06209C15.673 0.880101 15.5624 0.714755 15.4231 0.575488C15.2838 0.436221 15.1184 0.325762 14.9364 0.250416C14.7543 0.175071 14.5592 0.136315 14.3623 0.136361C14.1653 0.136408 13.9702 0.175255 13.7882 0.250687C13.6062 0.326118 13.4409 0.436656 13.3016 0.575988L7.9996 5.87899L2.6966 0.575988C2.5583 0.432659 2.39284 0.318309 2.20987 0.239611C2.0269 0.160912 1.83009 0.119441 1.63092 0.117617C1.43176 0.115793 1.23422 0.153652 1.04984 0.228987C0.865462 0.304321 0.697932 0.415622 0.557027 0.556394C0.416123 0.697166 0.304664 0.864591 0.229156 1.0489C0.153648 1.23321 0.115602 1.43071 0.117238 1.62988C0.118874 1.82905 0.16016 2.02589 0.238686 2.20894C0.317212 2.39198 0.431406 2.55755 0.574604 2.69599L5.8796 7.99999L0.575604 13.304C0.432406 13.4424 0.318212 13.608 0.239686 13.791C0.16116 13.9741 0.119874 14.1709 0.118238 14.3701C0.116602 14.5693 0.154648 14.7668 0.230156 14.9511C0.305664 15.1354 0.417122 15.3028 0.558027 15.4436C0.698932 15.5844 0.866462 15.6957 1.05084 15.771C1.23522 15.8463 1.43276 15.8842 1.63192 15.8824C1.83109 15.8805 2.0279 15.8391 2.21087 15.7604C2.39384 15.6817 2.5593 15.5673 2.6976 15.424L7.9996 10.122Z"/></svg></a></div><div><p>Something</p></div><div class="footer"><button data-is_form="1">Submit</button></div></div>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testPagination(): void
    {
        $hush = new HushBuilder();

        $result = $hush->pagination(
            currentPage: 2,
            pages: 15,
            baseLink: '/dashboard/products',
            paramName: 'page',
            params: ['sort_by' => 'name'],
        );

        $expectedResult = <<<HTML
        <div class="pagination"><a href="/dashboard/products?sort_by=name&page=1">1</a><a href="#">2</a><a href="/dashboard/products?sort_by=name&page=3">3</a><a href="/dashboard/products?sort_by=name&page=4">4</a><a href="/dashboard/products?sort_by=name&page=5">5</a><a href="/dashboard/products?sort_by=name&page=...">...</a><a href="/dashboard/products?sort_by=name&page=14">14</a><a href="/dashboard/products?sort_by=name&page=15">15</a></div>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testSelect(): void
    {
        $hush = new HushBuilder();

        $result = $hush->select(
            name: 'something',
            options: ['Nothing', 'Something'],
            value: 1,
            errors: ['Something is wrong'],
            placeholder: 'Pick something',
            searchPlaceholder: 'Search',
            isRequired: true,
            isSearchable: true,
            asyncSearchUrl: '/dashboard/something',
        );

        $expectedResult = <<<HTML
        <div class="select"    data-vanilla_template="    &lt;option value=&quot;{value}&quot; &gt;{text}&lt;/option&gt;"    data-template="    &lt;li data-value=&quot;{value}&quot; &gt;        &lt;div class=&quot;marked&quot;&gt;            &lt;svg width=&quot;16&quot; height=&quot;16&quot; viewBox=&quot;0 0 20 20&quot; fill=&quot;none&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;&gt;    &lt;path d=&quot;M16.2747 5.34176C16.5156 5.56945 16.6561 5.88345 16.6655 6.21474C16.6749 6.54604 16.5523 6.86749 16.3247 7.10843L9.24141 14.6084C9.12661 14.7298 8.98865 14.8269 8.8357 14.894C8.68274 14.9611 8.51788 14.9969 8.35086 14.9992C8.18383 15.0015 8.01804 14.9704 7.86327 14.9075C7.7085 14.8447 7.56789 14.7515 7.44974 14.6334L3.69974 10.8834C3.47894 10.6465 3.35874 10.3331 3.36445 10.0092C3.37017 9.68539 3.50135 9.37641 3.73037 9.14739C3.9594 8.91837 4.26837 8.78719 4.59221 8.78147C4.91604 8.77576 5.22945 8.89596 5.46641 9.11676L8.30808 11.9568L14.5081 5.39176C14.7358 5.15092 15.0498 5.01037 15.3811 5.00099C15.7123 4.99161 16.0338 5.11419 16.2747 5.34176Z&quot; fill=&quot;#4700C2&quot;/&gt;&lt;/svg&gt;        &lt;/div&gt;        {text}    &lt;/li&gt;"><div class="vanilla" style="display: none"><select name="something"><option value="0">Nothing</option><option value="1" selected="">Something</option></select></div><div class="input" data-placeholder="Pick something"><p>Something</p><svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 6L11 1" stroke="#4700C2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div><ul class="options"><div class="search-input margin-bottom-0"><div class="search"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.0961 5.90395C14.4946 5.29126 13.7776 4.80382 12.9866 4.46978C12.1956 4.13574 11.3463 3.96172 10.4877 3.95778C9.62911 3.95383 8.77823 4.12004 7.98421 4.44679C7.1902 4.77355 6.46879 5.25438 5.86166 5.86152C5.25452 6.46865 4.77369 7.19006 4.44694 7.98407C4.12018 8.77809 3.95398 9.62897 3.95792 10.4876C3.96187 11.3462 4.13588 12.1955 4.46992 12.9865C4.80396 13.7775 5.2914 14.4944 5.90409 15.0959C7.12684 16.2964 8.77418 16.9655 10.4877 16.9576C12.2013 16.9498 13.8424 16.2656 15.054 15.0539C16.2657 13.8422 16.9499 12.2011 16.9578 10.4876C16.9657 8.77404 16.2966 7.1267 15.0961 5.90395ZM4.49009 4.48995C6.02291 2.95751 8.08324 2.06852 10.2498 2.00478C12.4163 1.94103 14.5253 2.70735 16.1456 4.14702C17.7659 5.58669 18.7749 7.59094 18.9665 9.74992C19.158 11.9089 18.5176 14.0595 17.1761 15.7619L22.5211 21.1069L21.1071 22.5209L15.7621 17.1759C14.0591 18.5125 11.9103 19.149 9.75415 18.9555C7.59796 18.7621 5.5968 17.7534 4.15887 16.1351C2.72094 14.5167 1.95459 12.4108 2.01614 10.2468C2.07769 8.08288 2.9625 6.02391 4.49009 4.48995Z" fill="#999999"/></svg></div><input type="text" name="" value="" placeholder="Search" data-async_url="/dashboard/something"><small class="error"></small><div class="clear"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.47625 2 2 6.4775 2 12C2 17.5225 6.47625 22 12 22C17.5238 22 22 17.5225 22 12C22 6.4775 17.5238 2 12 2ZM16.6337 14.8663C16.8681 15.1006 16.9998 15.4185 16.9998 15.75C16.9998 16.0815 16.8681 16.3994 16.6337 16.6337C16.3994 16.8681 16.0815 16.9998 15.75 16.9998C15.4185 16.9998 15.1006 16.8681 14.8663 16.6337L12 13.7675L9.13375 16.6337C9.01793 16.7502 8.88023 16.8427 8.72857 16.9057C8.5769 16.9688 8.41426 17.0013 8.25 17.0013C8.08574 17.0013 7.9231 16.9688 7.77143 16.9057C7.61977 16.8427 7.48207 16.7502 7.36625 16.6337C7.25007 16.5178 7.1579 16.38 7.09501 16.2284C7.03212 16.0767 6.99975 15.9142 6.99975 15.75C6.99975 15.5858 7.03212 15.4233 7.09501 15.2716C7.1579 15.12 7.25007 14.9822 7.36625 14.8663L10.2325 12L7.36625 9.13375C7.13187 8.89936 7.00019 8.58147 7.00019 8.25C7.00019 7.91853 7.13187 7.60064 7.36625 7.36625C7.60064 7.13187 7.91853 7.00019 8.25 7.00019C8.58147 7.00019 8.89936 7.13187 9.13375 7.36625L12 10.2325L14.8663 7.36625C15.1006 7.13187 15.4185 7.00019 15.75 7.00019C16.0815 7.00019 16.3994 7.13187 16.6337 7.36625C16.8681 7.60064 16.9998 7.91853 16.9998 8.25C16.9998 8.58147 16.8681 8.89936 16.6337 9.13375L13.7675 12L16.6337 14.8663Z" fill="#999999"/></svg></div></div><li data-value="0"><div class="marked"><svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.2747 5.34176C16.5156 5.56945 16.6561 5.88345 16.6655 6.21474C16.6749 6.54604 16.5523 6.86749 16.3247 7.10843L9.24141 14.6084C9.12661 14.7298 8.98865 14.8269 8.8357 14.894C8.68274 14.9611 8.51788 14.9969 8.35086 14.9992C8.18383 15.0015 8.01804 14.9704 7.86327 14.9075C7.7085 14.8447 7.56789 14.7515 7.44974 14.6334L3.69974 10.8834C3.47894 10.6465 3.35874 10.3331 3.36445 10.0092C3.37017 9.68539 3.50135 9.37641 3.73037 9.14739C3.9594 8.91837 4.26837 8.78719 4.59221 8.78147C4.91604 8.77576 5.22945 8.89596 5.46641 9.11676L8.30808 11.9568L14.5081 5.39176C14.7358 5.15092 15.0498 5.01037 15.3811 5.00099C15.7123 4.99161 16.0338 5.11419 16.2747 5.34176Z" fill="#4700C2"/></svg></div>Nothing</li><li data-value="1" class="selected"><div class="marked"><svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.2747 5.34176C16.5156 5.56945 16.6561 5.88345 16.6655 6.21474C16.6749 6.54604 16.5523 6.86749 16.3247 7.10843L9.24141 14.6084C9.12661 14.7298 8.98865 14.8269 8.8357 14.894C8.68274 14.9611 8.51788 14.9969 8.35086 14.9992C8.18383 15.0015 8.01804 14.9704 7.86327 14.9075C7.7085 14.8447 7.56789 14.7515 7.44974 14.6334L3.69974 10.8834C3.47894 10.6465 3.35874 10.3331 3.36445 10.0092C3.37017 9.68539 3.50135 9.37641 3.73037 9.14739C3.9594 8.91837 4.26837 8.78719 4.59221 8.78147C4.91604 8.77576 5.22945 8.89596 5.46641 9.11676L8.30808 11.9568L14.5081 5.39176C14.7358 5.15092 15.0498 5.01037 15.3811 5.00099C15.7123 4.99161 16.0338 5.11419 16.2747 5.34176Z" fill="#4700C2"/></svg></div>Something</li></ul></div><small class="error">Something is wrong</small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testSelectMultiple(): void
    {
        $hush = new HushBuilder();

        $result = $hush->selectMultiple(
            name: 'something',
            options: ['Nothing', 'Something'],
            values: [0, 1],
            errors: ['Something is wrong'],
            placeholder: 'Placeholder',
            searchPlaceholder: 'Search',
            isRequired: true,
            isSearchable: true,
        );

        $expectedResult = <<<HTML
        <div class="select multiple"><div class="design" style="display: none"><li data-value="{value}">{text}<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.47625 2 2 6.4775 2 12C2 17.5225 6.47625 22 12 22C17.5238 22 22 17.5225 22 12C22 6.4775 17.5238 2 12 2ZM16.6337 14.8663C16.8681 15.1006 16.9998 15.4185 16.9998 15.75C16.9998 16.0815 16.8681 16.3994 16.6337 16.6337C16.3994 16.8681 16.0815 16.9998 15.75 16.9998C15.4185 16.9998 15.1006 16.8681 14.8663 16.6337L12 13.7675L9.13375 16.6337C9.01793 16.7502 8.88023 16.8427 8.72857 16.9057C8.5769 16.9688 8.41426 17.0013 8.25 17.0013C8.08574 17.0013 7.9231 16.9688 7.77143 16.9057C7.61977 16.8427 7.48207 16.7502 7.36625 16.6337C7.25007 16.5178 7.1579 16.38 7.09501 16.2284C7.03212 16.0767 6.99975 15.9142 6.99975 15.75C6.99975 15.5858 7.03212 15.4233 7.09501 15.2716C7.1579 15.12 7.25007 14.9822 7.36625 14.8663L10.2325 12L7.36625 9.13375C7.13187 8.89936 7.00019 8.58147 7.00019 8.25C7.00019 7.91853 7.13187 7.60064 7.36625 7.36625C7.60064 7.13187 7.91853 7.00019 8.25 7.00019C8.58147 7.00019 8.89936 7.13187 9.13375 7.36625L12 10.2325L14.8663 7.36625C15.1006 7.13187 15.4185 7.00019 15.75 7.00019C16.0815 7.00019 16.3994 7.13187 16.6337 7.36625C16.8681 7.60064 16.9998 7.91853 16.9998 8.25C16.9998 8.58147 16.8681 8.89936 16.6337 9.13375L13.7675 12L16.6337 14.8663Z" fill="#999999"/></svg></li></div><div class="vanilla" style="display: none"><select name="something[]" multiple><option value="0" selected="">Nothing</option><option value="1" selected="">Something</option></select></div><div class="input"><ul class="values "><li data-value="0">Nothing<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.47625 2 2 6.4775 2 12C2 17.5225 6.47625 22 12 22C17.5238 22 22 17.5225 22 12C22 6.4775 17.5238 2 12 2ZM16.6337 14.8663C16.8681 15.1006 16.9998 15.4185 16.9998 15.75C16.9998 16.0815 16.8681 16.3994 16.6337 16.6337C16.3994 16.8681 16.0815 16.9998 15.75 16.9998C15.4185 16.9998 15.1006 16.8681 14.8663 16.6337L12 13.7675L9.13375 16.6337C9.01793 16.7502 8.88023 16.8427 8.72857 16.9057C8.5769 16.9688 8.41426 17.0013 8.25 17.0013C8.08574 17.0013 7.9231 16.9688 7.77143 16.9057C7.61977 16.8427 7.48207 16.7502 7.36625 16.6337C7.25007 16.5178 7.1579 16.38 7.09501 16.2284C7.03212 16.0767 6.99975 15.9142 6.99975 15.75C6.99975 15.5858 7.03212 15.4233 7.09501 15.2716C7.1579 15.12 7.25007 14.9822 7.36625 14.8663L10.2325 12L7.36625 9.13375C7.13187 8.89936 7.00019 8.58147 7.00019 8.25C7.00019 7.91853 7.13187 7.60064 7.36625 7.36625C7.60064 7.13187 7.91853 7.00019 8.25 7.00019C8.58147 7.00019 8.89936 7.13187 9.13375 7.36625L12 10.2325L14.8663 7.36625C15.1006 7.13187 15.4185 7.00019 15.75 7.00019C16.0815 7.00019 16.3994 7.13187 16.6337 7.36625C16.8681 7.60064 16.9998 7.91853 16.9998 8.25C16.9998 8.58147 16.8681 8.89936 16.6337 9.13375L13.7675 12L16.6337 14.8663Z" fill="#999999"/></svg></li><li data-value="1">Something<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.47625 2 2 6.4775 2 12C2 17.5225 6.47625 22 12 22C17.5238 22 22 17.5225 22 12C22 6.4775 17.5238 2 12 2ZM16.6337 14.8663C16.8681 15.1006 16.9998 15.4185 16.9998 15.75C16.9998 16.0815 16.8681 16.3994 16.6337 16.6337C16.3994 16.8681 16.0815 16.9998 15.75 16.9998C15.4185 16.9998 15.1006 16.8681 14.8663 16.6337L12 13.7675L9.13375 16.6337C9.01793 16.7502 8.88023 16.8427 8.72857 16.9057C8.5769 16.9688 8.41426 17.0013 8.25 17.0013C8.08574 17.0013 7.9231 16.9688 7.77143 16.9057C7.61977 16.8427 7.48207 16.7502 7.36625 16.6337C7.25007 16.5178 7.1579 16.38 7.09501 16.2284C7.03212 16.0767 6.99975 15.9142 6.99975 15.75C6.99975 15.5858 7.03212 15.4233 7.09501 15.2716C7.1579 15.12 7.25007 14.9822 7.36625 14.8663L10.2325 12L7.36625 9.13375C7.13187 8.89936 7.00019 8.58147 7.00019 8.25C7.00019 7.91853 7.13187 7.60064 7.36625 7.36625C7.60064 7.13187 7.91853 7.00019 8.25 7.00019C8.58147 7.00019 8.89936 7.13187 9.13375 7.36625L12 10.2325L14.8663 7.36625C15.1006 7.13187 15.4185 7.00019 15.75 7.00019C16.0815 7.00019 16.3994 7.13187 16.6337 7.36625C16.8681 7.60064 16.9998 7.91853 16.9998 8.25C16.9998 8.58147 16.8681 8.89936 16.6337 9.13375L13.7675 12L16.6337 14.8663Z" fill="#999999"/></svg></li></ul><p>Placeholder</p><svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 6L11 1" stroke="#4700C2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div><ul class="options"><div class="search-input margin-bottom-0"><div class="search"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.0961 5.90395C14.4946 5.29126 13.7776 4.80382 12.9866 4.46978C12.1956 4.13574 11.3463 3.96172 10.4877 3.95778C9.62911 3.95383 8.77823 4.12004 7.98421 4.44679C7.1902 4.77355 6.46879 5.25438 5.86166 5.86152C5.25452 6.46865 4.77369 7.19006 4.44694 7.98407C4.12018 8.77809 3.95398 9.62897 3.95792 10.4876C3.96187 11.3462 4.13588 12.1955 4.46992 12.9865C4.80396 13.7775 5.2914 14.4944 5.90409 15.0959C7.12684 16.2964 8.77418 16.9655 10.4877 16.9576C12.2013 16.9498 13.8424 16.2656 15.054 15.0539C16.2657 13.8422 16.9499 12.2011 16.9578 10.4876C16.9657 8.77404 16.2966 7.1267 15.0961 5.90395ZM4.49009 4.48995C6.02291 2.95751 8.08324 2.06852 10.2498 2.00478C12.4163 1.94103 14.5253 2.70735 16.1456 4.14702C17.7659 5.58669 18.7749 7.59094 18.9665 9.74992C19.158 11.9089 18.5176 14.0595 17.1761 15.7619L22.5211 21.1069L21.1071 22.5209L15.7621 17.1759C14.0591 18.5125 11.9103 19.149 9.75415 18.9555C7.59796 18.7621 5.5968 17.7534 4.15887 16.1351C2.72094 14.5167 1.95459 12.4108 2.01614 10.2468C2.07769 8.08288 2.9625 6.02391 4.49009 4.48995Z" fill="#999999"/></svg></div><input type="text" name="" value="" placeholder="Search"><small class="error"></small><div class="clear"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.47625 2 2 6.4775 2 12C2 17.5225 6.47625 22 12 22C17.5238 22 22 17.5225 22 12C22 6.4775 17.5238 2 12 2ZM16.6337 14.8663C16.8681 15.1006 16.9998 15.4185 16.9998 15.75C16.9998 16.0815 16.8681 16.3994 16.6337 16.6337C16.3994 16.8681 16.0815 16.9998 15.75 16.9998C15.4185 16.9998 15.1006 16.8681 14.8663 16.6337L12 13.7675L9.13375 16.6337C9.01793 16.7502 8.88023 16.8427 8.72857 16.9057C8.5769 16.9688 8.41426 17.0013 8.25 17.0013C8.08574 17.0013 7.9231 16.9688 7.77143 16.9057C7.61977 16.8427 7.48207 16.7502 7.36625 16.6337C7.25007 16.5178 7.1579 16.38 7.09501 16.2284C7.03212 16.0767 6.99975 15.9142 6.99975 15.75C6.99975 15.5858 7.03212 15.4233 7.09501 15.2716C7.1579 15.12 7.25007 14.9822 7.36625 14.8663L10.2325 12L7.36625 9.13375C7.13187 8.89936 7.00019 8.58147 7.00019 8.25C7.00019 7.91853 7.13187 7.60064 7.36625 7.36625C7.60064 7.13187 7.91853 7.00019 8.25 7.00019C8.58147 7.00019 8.89936 7.13187 9.13375 7.36625L12 10.2325L14.8663 7.36625C15.1006 7.13187 15.4185 7.00019 15.75 7.00019C16.0815 7.00019 16.3994 7.13187 16.6337 7.36625C16.8681 7.60064 16.9998 7.91853 16.9998 8.25C16.9998 8.58147 16.8681 8.89936 16.6337 9.13375L13.7675 12L16.6337 14.8663Z" fill="#999999"/></svg></div></div><li data-value="0" class="selected"><div class="marked"><svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.2747 5.34176C16.5156 5.56945 16.6561 5.88345 16.6655 6.21474C16.6749 6.54604 16.5523 6.86749 16.3247 7.10843L9.24141 14.6084C9.12661 14.7298 8.98865 14.8269 8.8357 14.894C8.68274 14.9611 8.51788 14.9969 8.35086 14.9992C8.18383 15.0015 8.01804 14.9704 7.86327 14.9075C7.7085 14.8447 7.56789 14.7515 7.44974 14.6334L3.69974 10.8834C3.47894 10.6465 3.35874 10.3331 3.36445 10.0092C3.37017 9.68539 3.50135 9.37641 3.73037 9.14739C3.9594 8.91837 4.26837 8.78719 4.59221 8.78147C4.91604 8.77576 5.22945 8.89596 5.46641 9.11676L8.30808 11.9568L14.5081 5.39176C14.7358 5.15092 15.0498 5.01037 15.3811 5.00099C15.7123 4.99161 16.0338 5.11419 16.2747 5.34176Z" fill="#4700C2"/></svg></div>Nothing</li><li data-value="1" class="selected"><div class="marked"><svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.2747 5.34176C16.5156 5.56945 16.6561 5.88345 16.6655 6.21474C16.6749 6.54604 16.5523 6.86749 16.3247 7.10843L9.24141 14.6084C9.12661 14.7298 8.98865 14.8269 8.8357 14.894C8.68274 14.9611 8.51788 14.9969 8.35086 14.9992C8.18383 15.0015 8.01804 14.9704 7.86327 14.9075C7.7085 14.8447 7.56789 14.7515 7.44974 14.6334L3.69974 10.8834C3.47894 10.6465 3.35874 10.3331 3.36445 10.0092C3.37017 9.68539 3.50135 9.37641 3.73037 9.14739C3.9594 8.91837 4.26837 8.78719 4.59221 8.78147C4.91604 8.77576 5.22945 8.89596 5.46641 9.11676L8.30808 11.9568L14.5081 5.39176C14.7358 5.15092 15.0498 5.01037 15.3811 5.00099C15.7123 4.99161 16.0338 5.11419 16.2747 5.34176Z" fill="#4700C2"/></svg></div>Something</li></ul></div><small class="error">Something is wrong</small>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testStyle(): void
    {
        $hush = new HushBuilder();

        $result = $hush->style('.body {background: #fff}');

        $expectedResult = <<<HTML
        <style>.body {background: #fff}</style>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testTable(): void
    {
        $hush = new HushBuilder();

        $result = $hush->table(
            rows: [1, 2, 3],
            isCheckboxColumnEnabled: true,
            checkboxName: fn (int $item) => 'items[' . $item . ']',
            actionsHeader: 'Actions',
            attributes: ['class' => 'something'],
            table: fn (Table $table) => $table
                ->addColumn('ID', fn (int $item) => $item)
                ->addAction(
                    note: 'Edit',
                    link: '/dashboards/products/edit',
                    condition: fn (int $item) => $item > 1,
                    icon: Svg::Edit->render(),
                )
                ->addAction(
                    note: 'Delete',
                    link: '/dashboards/products/delete',
                    condition: fn (int $item) => $item > 1,
                    icon: Svg::Delete->render(),
                    isAsyncModal: true,
                ),
        );

        $expectedResult = <<<HTML
        <table class="something"><thead><tr><th><label class="checkbox margin-bottom-0"><input type="checkbox" name="check-all" value="1"><div class="checked"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.2747 5.34176C16.5156 5.56945 16.6561 5.88345 16.6655 6.21474C16.6749 6.54604 16.5523 6.86749 16.3247 7.10843L9.24141 14.6084C9.12661 14.7298 8.98865 14.8269 8.8357 14.894C8.68274 14.9611 8.51788 14.9969 8.35086 14.9992C8.18383 15.0015 8.01804 14.9704 7.86327 14.9075C7.7085 14.8447 7.56789 14.7515 7.44974 14.6334L3.69974 10.8834C3.47894 10.6465 3.35874 10.3331 3.36445 10.0092C3.37017 9.68539 3.50135 9.37641 3.73037 9.14739C3.9594 8.91837 4.26837 8.78719 4.59221 8.78147C4.91604 8.77576 5.22945 8.89596 5.46641 9.11676L8.30808 11.9568L14.5081 5.39176C14.7358 5.15092 15.0498 5.01037 15.3811 5.00099C15.7123 4.99161 16.0338 5.11419 16.2747 5.34176Z" fill="#4700C2"/></svg></div><div class="unchecked"></div><p></p></label></th><th>ID</th><th>Actions</th></tr></thead><tbody><tr><td><label class="checkbox margin-bottom-0"><input type="checkbox" name="items[1]" value="1"><div class="checked"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.2747 5.34176C16.5156 5.56945 16.6561 5.88345 16.6655 6.21474C16.6749 6.54604 16.5523 6.86749 16.3247 7.10843L9.24141 14.6084C9.12661 14.7298 8.98865 14.8269 8.8357 14.894C8.68274 14.9611 8.51788 14.9969 8.35086 14.9992C8.18383 15.0015 8.01804 14.9704 7.86327 14.9075C7.7085 14.8447 7.56789 14.7515 7.44974 14.6334L3.69974 10.8834C3.47894 10.6465 3.35874 10.3331 3.36445 10.0092C3.37017 9.68539 3.50135 9.37641 3.73037 9.14739C3.9594 8.91837 4.26837 8.78719 4.59221 8.78147C4.91604 8.77576 5.22945 8.89596 5.46641 9.11676L8.30808 11.9568L14.5081 5.39176C14.7358 5.15092 15.0498 5.01037 15.3811 5.00099C15.7123 4.99161 16.0338 5.11419 16.2747 5.34176Z" fill="#4700C2"/></svg></div><div class="unchecked"></div><p></p></label></td><td>1</td><td><div class="actions"></div></td></tr><tr><td><label class="checkbox margin-bottom-0"><input type="checkbox" name="items[2]" value="1"><div class="checked"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.2747 5.34176C16.5156 5.56945 16.6561 5.88345 16.6655 6.21474C16.6749 6.54604 16.5523 6.86749 16.3247 7.10843L9.24141 14.6084C9.12661 14.7298 8.98865 14.8269 8.8357 14.894C8.68274 14.9611 8.51788 14.9969 8.35086 14.9992C8.18383 15.0015 8.01804 14.9704 7.86327 14.9075C7.7085 14.8447 7.56789 14.7515 7.44974 14.6334L3.69974 10.8834C3.47894 10.6465 3.35874 10.3331 3.36445 10.0092C3.37017 9.68539 3.50135 9.37641 3.73037 9.14739C3.9594 8.91837 4.26837 8.78719 4.59221 8.78147C4.91604 8.77576 5.22945 8.89596 5.46641 9.11676L8.30808 11.9568L14.5081 5.39176C14.7358 5.15092 15.0498 5.01037 15.3811 5.00099C15.7123 4.99161 16.0338 5.11419 16.2747 5.34176Z" fill="#4700C2"/></svg></div><div class="unchecked"></div><p></p></label></td><td>2</td><td><div class="actions"><a href="/dashboards/products/edit" title="Edit"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.988 2.01196L21.988 5.01196L19.701 7.29996L16.701 4.29996L18.988 2.01196ZM8 16H11L18.287 8.71296L15.287 5.71296L8 13V16Z" fill="#5B34F0"/><path d="M19 19H8.158C8.132 19 8.105 19.01 8.079 19.01C8.046 19.01 8.013 19.001 7.979 19H5V5H11.847L13.847 3H5C3.897 3 3 3.896 3 5V19C3 20.104 3.897 21 5 21H19C19.5304 21 20.0391 20.7893 20.4142 20.4142C20.7893 20.0391 21 19.5304 21 19V10.332L19 12.332V19Z" fill="#5B34F0"/></svg></a><a href="/dashboards/products/delete" title="Delete" class="async-modal-link"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.47625 2 2 6.4775 2 12C2 17.5225 6.47625 22 12 22C17.5238 22 22 17.5225 22 12C22 6.4775 17.5238 2 12 2ZM16.6337 14.8663C16.8681 15.1006 16.9998 15.4185 16.9998 15.75C16.9998 16.0815 16.8681 16.3994 16.6337 16.6337C16.3994 16.8681 16.0815 16.9998 15.75 16.9998C15.4185 16.9998 15.1006 16.8681 14.8663 16.6337L12 13.7675L9.13375 16.6337C9.01793 16.7502 8.88023 16.8427 8.72857 16.9057C8.5769 16.9688 8.41426 17.0013 8.25 17.0013C8.08574 17.0013 7.9231 16.9688 7.77143 16.9057C7.61977 16.8427 7.48207 16.7502 7.36625 16.6337C7.25007 16.5178 7.1579 16.38 7.09501 16.2284C7.03212 16.0767 6.99975 15.9142 6.99975 15.75C6.99975 15.5858 7.03212 15.4233 7.09501 15.2716C7.1579 15.12 7.25007 14.9822 7.36625 14.8663L10.2325 12L7.36625 9.13375C7.13187 8.89936 7.00019 8.58147 7.00019 8.25C7.00019 7.91853 7.13187 7.60064 7.36625 7.36625C7.60064 7.13187 7.91853 7.00019 8.25 7.00019C8.58147 7.00019 8.89936 7.13187 9.13375 7.36625L12 10.2325L14.8663 7.36625C15.1006 7.13187 15.4185 7.00019 15.75 7.00019C16.0815 7.00019 16.3994 7.13187 16.6337 7.36625C16.8681 7.60064 16.9998 7.91853 16.9998 8.25C16.9998 8.58147 16.8681 8.89936 16.6337 9.13375L13.7675 12L16.6337 14.8663Z" fill="#EE4392"/></svg></a></div></td></tr><tr><td><label class="checkbox margin-bottom-0"><input type="checkbox" name="items[3]" value="1"><div class="checked"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.2747 5.34176C16.5156 5.56945 16.6561 5.88345 16.6655 6.21474C16.6749 6.54604 16.5523 6.86749 16.3247 7.10843L9.24141 14.6084C9.12661 14.7298 8.98865 14.8269 8.8357 14.894C8.68274 14.9611 8.51788 14.9969 8.35086 14.9992C8.18383 15.0015 8.01804 14.9704 7.86327 14.9075C7.7085 14.8447 7.56789 14.7515 7.44974 14.6334L3.69974 10.8834C3.47894 10.6465 3.35874 10.3331 3.36445 10.0092C3.37017 9.68539 3.50135 9.37641 3.73037 9.14739C3.9594 8.91837 4.26837 8.78719 4.59221 8.78147C4.91604 8.77576 5.22945 8.89596 5.46641 9.11676L8.30808 11.9568L14.5081 5.39176C14.7358 5.15092 15.0498 5.01037 15.3811 5.00099C15.7123 4.99161 16.0338 5.11419 16.2747 5.34176Z" fill="#4700C2"/></svg></div><div class="unchecked"></div><p></p></label></td><td>3</td><td><div class="actions"><a href="/dashboards/products/edit" title="Edit"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.988 2.01196L21.988 5.01196L19.701 7.29996L16.701 4.29996L18.988 2.01196ZM8 16H11L18.287 8.71296L15.287 5.71296L8 13V16Z" fill="#5B34F0"/><path d="M19 19H8.158C8.132 19 8.105 19.01 8.079 19.01C8.046 19.01 8.013 19.001 7.979 19H5V5H11.847L13.847 3H5C3.897 3 3 3.896 3 5V19C3 20.104 3.897 21 5 21H19C19.5304 21 20.0391 20.7893 20.4142 20.4142C20.7893 20.0391 21 19.5304 21 19V10.332L19 12.332V19Z" fill="#5B34F0"/></svg></a><a href="/dashboards/products/delete" title="Delete" class="async-modal-link"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.47625 2 2 6.4775 2 12C2 17.5225 6.47625 22 12 22C17.5238 22 22 17.5225 22 12C22 6.4775 17.5238 2 12 2ZM16.6337 14.8663C16.8681 15.1006 16.9998 15.4185 16.9998 15.75C16.9998 16.0815 16.8681 16.3994 16.6337 16.6337C16.3994 16.8681 16.0815 16.9998 15.75 16.9998C15.4185 16.9998 15.1006 16.8681 14.8663 16.6337L12 13.7675L9.13375 16.6337C9.01793 16.7502 8.88023 16.8427 8.72857 16.9057C8.5769 16.9688 8.41426 17.0013 8.25 17.0013C8.08574 17.0013 7.9231 16.9688 7.77143 16.9057C7.61977 16.8427 7.48207 16.7502 7.36625 16.6337C7.25007 16.5178 7.1579 16.38 7.09501 16.2284C7.03212 16.0767 6.99975 15.9142 6.99975 15.75C6.99975 15.5858 7.03212 15.4233 7.09501 15.2716C7.1579 15.12 7.25007 14.9822 7.36625 14.8663L10.2325 12L7.36625 9.13375C7.13187 8.89936 7.00019 8.58147 7.00019 8.25C7.00019 7.91853 7.13187 7.60064 7.36625 7.36625C7.60064 7.13187 7.91853 7.00019 8.25 7.00019C8.58147 7.00019 8.89936 7.13187 9.13375 7.36625L12 10.2325L14.8663 7.36625C15.1006 7.13187 15.4185 7.00019 15.75 7.00019C16.0815 7.00019 16.3994 7.13187 16.6337 7.36625C16.8681 7.60064 16.9998 7.91853 16.9998 8.25C16.9998 8.58147 16.8681 8.89936 16.6337 9.13375L13.7675 12L16.6337 14.8663Z" fill="#EE4392"/></svg></a></div></td></tr></tbody></table>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testText(): void
    {
        $hush = new HushBuilder();

        $result = $hush->style('.body {background: #fff}');

        $expectedResult = <<<HTML
        <style>.body {background: #fff}</style>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function testTextarea(): void
    {
        $hush = new HushBuilder();

        $result = $hush->text('Something', ['class' => 'something']);

        $expectedResult = <<<HTML
        <p class="something">Something</p>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }

    public function video(): void
    {
        $hush = new HushBuilder();

        $result = $hush->video('something.mp4', ['class' => 'something']);

        $expectedResult = <<<HTML
        <video src="something.mp4" class="something"></video>
        HTML;

        $this->assertEquals($expectedResult, $result->render());
    }
}

@php
    use Picqer\Barcode\BarcodeGeneratorSVG;
    $generator = new BarcodeGeneratorSVG();
    $sku = $getRecord()->sku;
    $barcodeSvg = $generator->getBarcode($sku, $generator::TYPE_CODE_128, 3, 80);
@endphp

<div class="flex flex-col items-start gap-2 print:block">
    <div class="barcode-container p-4 bg-white border border-gray-200 dark:border-gray-700 rounded-lg inline-block">
        {!! $barcodeSvg !!}
        <p class="text-center text-sm font-mono text-gray-600 dark:text-gray-400 mt-1">{{ $sku }}</p>
    </div>
</div>

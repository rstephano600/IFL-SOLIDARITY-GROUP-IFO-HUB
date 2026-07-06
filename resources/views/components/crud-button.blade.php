@props([
    'type' => 'button', // add, edit, delete
    'url' => '',
    'itemId' => null,
    'itemName' => '',
    'size' => 'md',
    'class' => '',
    'icon' => null,
    'text' => null
])

@php
    $configs = [
        'add' => [
            'icon' => 'bi-plus-lg',
            'class' => 'btn-primary',
            'text' => 'Add',
            'method' => 'POST',
            'title' => 'Add New'
        ],
        'edit' => [
            'icon' => 'bi-pencil',
            'class' => 'btn-warning',
            'text' => 'Edit',
            'method' => 'PUT',
            'title' => 'Edit'
        ],
        'delete' => [
            'icon' => 'bi-trash',
            'class' => 'btn-danger',
            'text' => 'Delete',
            'method' => 'DELETE',
            'title' => 'Delete'
        ]
    ];
    
    $config = $configs[$type];
    $buttonClass = $class ?: $config['class'];
    $buttonIcon = $icon ?: $config['icon'];
    $buttonText = $text ?: $config['text'];
    $modalTitle = $config['title'] . ($itemName ? " — {$itemName}" : '');
@endphp

@if($type === 'delete')
    <button type="button" 
            class="btn btn-sm {{ $buttonClass }}"
            onclick="CRUD.confirmDelete({
                url: '{{ $url }}',
                itemName: '{{ $itemName }}',
                method: 'DELETE'
            })">
        <i class="bi {{ $buttonIcon }}"></i> {{ $buttonText }}
    </button>
@else
    <button type="button" 
            class="btn btn-sm {{ $buttonClass }}"
            onclick="CRUD.openModal({
                modalId: 'globalModal',
                title: '{{ $modalTitle }}',
                actionUrl: '{{ $url }}',
                method: '{{ $config['method'] }}',
                size: '{{ $size }}',
                submitText: '{{ $buttonText }}'
            })">
        <i class="bi {{ $buttonIcon }}"></i> {{ $buttonText }}
    </button>
@endif
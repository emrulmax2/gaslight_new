@aware(['selectedIndex' => null, 'index' => null])
@aware(['id' => null])

<div
    id="{{ $id }}-collapse"
    aria-labelledby="{{ $id }}"
    @class([
        'accordion-collapse collapse mt-3 text-slate-700 leading-relaxed dark:text-slate-400',
        '[&.collapse:not(.show)]:hidden [&.collapse.show]:visible',
        'show' => $selectedIndex == $index,
    ])
>
    <div
        data-tw-merge
        {{ $attributes->class(merge(['accordion-body', $attributes->whereStartsWith('class')->first()]))->merge($attributes->whereDoesntStartWith('class')->getAttributes()) }}
    >
        {{ $slot }}
    </div>
</div>

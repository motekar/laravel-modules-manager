<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <script src="https://unpkg.com/tailwindcss-jit-cdn"></script>
    </head>
    <body>
        <div class="p-8">
            @if ($message = Session::get('error'))
            <div class="px-4 py-3 mb-8 border border-red-200 bg-red-50 rounded text-red-800">
                <p>{!! $message !!}</p>
            </div>
            @endif

            <table class="w-full border-t border-l">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="text-left border-r border-b px-4 py-2">Module</th>
                        <th class="text-left border-r border-b px-4 py-2">Status</th>
                        <th class="text-left border-r border-b px-4 py-2">Requires</th>
                        <th class="text-left border-r border-b px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modules as $module)
                        <tr class="hover:bg-gray-50">
                            <td class="border-r border-b px-4 py-2">
                                <p class="font-semibold">{{ $module['name'] }}</p>
                                @if ($module['description'])
                                    <p class="mt-1 text-sm">{{ $module['description'] }}</p>
                                @endif
                            </td>
                            <td class="border-r border-b px-4 py-2">
                                @if ($module['is_core'])
                                    <p class="inline-block text-sm font-semibold text-gray-600 bg-gray-100 rounded px-2 py-1">Core</p>
                                @elseif ($module['enabled'])
                                    <p class="inline-block text-sm font-semibold text-green-600 bg-green-100 rounded px-2 py-1">Enabled</p>
                                @else
                                    <p class="inline-block text-sm font-semibold text-red-600 bg-red-100 rounded px-2 py-1">Disabled</p>
                                @endif
                            </td>
                            <td class="border-r border-b px-4 py-2">
                                {{ implode(', ', $module['requires']) }}
                            </td>
                            <td class="border-r border-b px-4 py-2">
                                @if (!$module['is_core'])
                                <form action="{{ route('modules.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="{{ $module['enabled'] ? 'disable' : 'enable' }}">
                                    <input type="hidden" name="module" value="{{ $module['name'] }}">
                                    <button class="font-semibold text-sm rounded bg-gray-50 border px-3 py-2 shadow hover:bg-gray-100" type="submit">{{ $module['enabled'] ? 'Disable' : 'Enable' }}</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>

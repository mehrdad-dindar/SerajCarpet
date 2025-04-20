<div class="space-y-4">
    @foreach($comments as $comment)
        <div class="p-4 border rounded">
            <div class="flex justify-between">
                <div class="flex justify-between gap-2 items-center">
                    <x-phosphor.icons::duotone.user-circle class="w-4"/>
                    <span class="font-semibold">{{ $comment->commenter->name }}</span>
                </div>
                <div class="flex justify-between gap-2 items-center">
                    <x-phosphor.icons::duotone.clock class="w-4"/>
                    <span class="text-sm text-gray-500"
                          title="{{ $comment->created_at->toJalali()->format('d F Y - H:i') }}">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <p class="mt-2 border-s border-warning-400 ps-1">{!! nl2br(e($comment->body)) !!}</p>
        </div>
    @endforeach
</div>

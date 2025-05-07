<div class="space-y-4">
    @if($comments->count())
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
            <div class="relative mt-2 border-s border-warning-400 ps-1 ms-2">
                <x-phosphor.icons::duotone.chat class="w-4 absolute top-0 -right-5 text-warning-400"/>
                <p>{!! nl2br(e($comment->body)) !!}</p>
            </div>
        </div>
    @endforeach
    @else
    <p>هیچ توضیحی وجود ندارد!</p>
    @endif
</div>

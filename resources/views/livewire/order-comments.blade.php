<div class="space-y-4 max-h-[600px] overflow-y-auto">
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
                          title="{{ $comment->created_at->toJalali()->format('d F Y - H:i') }}">{{ $comment->created_at->diffForHumans() }}
    ({{ $comment->created_at->toJalali()->format('Y/m/d H:i') }})</span>
                </div>
            </div>
            <div class="relative mt-2 border-s border-warning-400 ps-1 ms-2">
                <x-phosphor.icons::duotone.chat class="w-4 absolute top-0 -right-5 text-warning-400"/>
                @if(is_null($comment->body))
                    @php
                        $voice_note = $comment->getMedia('voice_notes');
                    @endphp
                    @if($voice_note)
                        <audio controls>
                            <source src="{!! $voice_note[0]->getUrl() !!}" type="{!! $voice_note[0]->mime_type !!}">
                            Your browser does not support the audio tag.
                        </audio>
                    @endif
                @else
                <p>{!! nl2br(e($comment->body)) !!}</p>
                @endif
            </div>
        </div>
    @endforeach
        {{ $comments->links() }} <!-- افزودن pagination links -->
    @else
    <p>هیچ توضیحی وجود ندارد!</p>
    @endif
</div>

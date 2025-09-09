<!-- Avatar Upload Component -->
<div class="text-center mb-4 d-flex flex-column align-items-center">
    <div class="avatar-upload">
        <div class="avatar-edit">
            <input type="file" id="imageUpload" name="avatar" accept=".png,.jpg,.jpeg" data-preview="#imgPreview">
            <label for="imageUpload"><i class="ti ti-photo-heart"></i></label>
        </div>
        <div class="avatar-preview">
            <div id="imgPreview" style="background-image: url('{{ Auth::user()->getAvatarUrl() }}'); {{ !Auth::user()->getAvatarUrl() ? Auth::user()->getAvatarBackgroundStyle() : '' }}">
                @if(!Auth::user()->getAvatarUrl())
                    <div class="d-flex align-items-center justify-content-center h-100" style="{{ Auth::user()->getAvatarBackgroundStyle() }}">
                        <span class="text-white fw-bold" style="font-size: 2.5rem;">{{ Auth::user()->getInitials() }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="mt-3 text-center">
        <a href="mailto:{{ $user->email }}" class="text-decoration-none">
            <i class="ti ti-mail me-1"></i>
            {{ $user->email }}
        </a>
        <small class="text-muted d-block">(Private — cannot be changed)</small>
    </div>
</div>

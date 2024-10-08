<li class="nav-item">
    <a class="nav-link" data-bs-toggle="collapse" href="#{{$id}}" aria-expanded="false" aria-controls="{{$id}}">
        <i class="{{ $icon }} menu-icon"></i>
        <span class="menu-title">{{ $title }}</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="collapse" id="{{$id}}">
        <ul class="nav flex-column sub-menu">{{ $slot }}</ul>
    </div>
</li>

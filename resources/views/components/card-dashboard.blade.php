  <div class="card {{ $class }}" id="{{ $id }}">
    <div class="card-body">
        <h4 class="card-title">{{ $title }}</h4>
          <p class="card-description">
              {{ $desc }}
          </p>
          {{ $slot }}
      </div>
  </div>

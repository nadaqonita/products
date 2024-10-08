<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <style>
        body {
            margin: 2rem;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-sm-12">
            <x-card>
                <x-card-body>
                    <div class="d-flex align-items-center mb-4">
                        <h4 class="card-title">{{ $title }}</h4>
                        <x-button id="refresh" class="btn btn-secondary btn-sm rounded ms-auto"><i
                                class="mdi mdi-refresh"></i>
                        </x-button>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                {{ $dataTable->table() }}
                            </div>
                        </div>
                    </div>
                </x-card-body>
            </x-card>
        </div>
    </div>

    @include('products.table._partials._modal')

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.7/dist/notiflix-aio-3.2.7.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/scripts/verify.min.js"></script>

    {!! $dataTable->scripts() !!}

    <script>
        $(function() {
            $('#refresh').on('click', function() {
                $('#base-table').DataTable().ajax.reload()
            })

            {!! $script ?? '' !!}
        })

        $(document).on('input', '.NumberOnly', function(event) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>

</html>

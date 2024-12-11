@extends('layouts.app')
@section('title')
    NOTIFICATIONS
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered m-0 datatable">
                    <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Screen</th>
                            <th>Action</th>
                            <th>Message</th>
                            <th>DateTime</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            @foreach (['Sr #', 'Screen', 'Action', 'Message', 'DateTime'] as $index => $colName)
                                <th><input type="text" placeholder="Search {{ $colName }}" style="width: 150px;"></th>
                            @endforeach
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        var table;
        $(document).ready(function() {
            table = $('.datatable').DataTable({
                order: [],
                columnDefs: [{
                    targets: '_all',
                    sortable: false
                }],
                initComplete: function() {
                    this.api()
                        .columns()
                        .every(function() {
                            let column = this;
                            let title = column.footer().textContent;

                            let input = document.createElement('input');
                            input.placeholder = 'Search ' + title;
                            column.footer().replaceChildren(input);

                            input.addEventListener('keyup', () => {
                                if (column.search() !== this.value) {
                                    column.search(input.value).draw();
                                }
                            });
                        });
                }
            });
            fetchNotification();
        });

        $(document).on('click', '.mark-as-read-btn', function() {
            $('button').prop('disabled', true);
            const notificationId = $(this).data('notification-id');
            const route = $(this).data('id');
            $.ajax({
                url: `{{ route('notifications.markAsRead', ['id' => ':id']) }}`.replace(':id',
                    notificationId),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    window.location.href = route;
                }.bind(this),
                error: function(xhr, status, error) {
                    console.error('Error marking notification as read:', error);
                }
            });
        });

        function fetchNotification() {
            let currentPage = table.page();
            $.ajax({
                url: "{{ route('notifications.all') }}",
                type: 'GET',
                success: function(response) {
                    table.clear().draw();
                    if (response.notifications) {
                        $.each(response.notifications, function(index, notification) {
                            var notificationItem = notification.data;
                            table.row.add([
                                index + 1,
                                notificationItem.screen,
                                notificationItem.action,
                                notificationItem.message,
                                `${notificationItem.datetime}
                                    <button class="btn btn-sm btn-success mark-as-read-btn ms-4"
                                    data-notification-id="${ notification.id }" data-id="${notificationItem.route}">View</button>`
                            ]);
                        });
                        table.draw();
                        table.page(currentPage).draw(false);
                    }
                },
                error: function(xhr) {
                    console.log("Error fetching notification count", xhr.responseText);
                }
            });
        }

        setInterval(fetchNotification, 10000);
    </script>
@endsection

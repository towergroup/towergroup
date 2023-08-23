jQuery('[data-show-contacts]').on('click', function (e) {
    e.preventDefault();
    var brokerId = $(this).attr('data-show-contacts');
    var contactsHtml = '<ul class="list">' +
    '<li class="list-item"><a class="list-link" href="tel:'+brokers[brokerId].tel+'">'+brokers[brokerId].phone+'</a></li>' +
    '<li class="list-item"><a class="list-link" href="mailto:'+brokers[brokerId].email+'">'+brokers[brokerId].email+'</a></li>' +
    '</ul>'
    $(this).after(contactsHtml);
    $(this).remove();
});
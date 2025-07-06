
let project = $('.filter-project').val();
$(".filter-project").change(function () {
    project = $(this).val()
    let url = new URL(link);
    url.searchParams.set('project', project);
    window.location.href = url;
});
//Tạo tabble
$('div#table-search').Grid({
    columns: ['ID', 'Name', 'Package', 'Email', 'Phone', 'Address'],
    search: {
        server: {
            url: (prev, keyword) => `${prev}?search=${keyword}&project=${project}`
        }
    },
    pagination: {
        limit: 10,
        server: {
            url: (prev, page, limit) => `${prev}${prev.includes('?') ? '&' : '?'}limit=${limit}&page=${page + 1}&project=${project}`
        }
    },

    server: {
        url: show_customer,
        then: data => data.result.map(pokemon => [
            pokemon.id,
            pokemon.name,
            gridjs.html(pokemon.package),
            pokemon.email,
            pokemon.phone,
            pokemon.address,
            // pokemon.date,
        ]),
        total: data => data.count
    }
});





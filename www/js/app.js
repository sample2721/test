Ext.onReady(function() {

    var store = new Ext.data.JsonStore({
        url: 'data.php',
        root: 'log',
        idProperty: 'ip',
        totalProperty: 'total',
        fields: [
            {name: 'ip', type: 'string'},
            {name: 'os'},
            {name: 'browser'},
            {name: 'referer'},
            {name: 'url'},
            {name: 'count'}]
    });

    var colModel = new Ext.grid.ColumnModel({
        columns: [
            {dataIndex: 'ip', header: 'IP',},
            {dataIndex: 'browser', header: 'Browser', sortable: true},
            {dataIndex: 'os', header: 'OS', sortable: true},
            {dataIndex: 'referer', header: 'Referer'},
            {dataIndex: 'url', header: 'URL'},
            {dataIndex: 'count', header: 'Uniq URL count',}
        ],
        defaults: {
            sortable: false,
            width: 200
        }
    });

    var filters = new Ext.ux.grid.GridFilters({
//        encode: true,
        //local: false,   // defaults to false (remote filtering)
        filters: [
            {type: 'string', dataIndex: 'ip'}
            ]
    });

    var grid = new Ext.grid.GridPanel({
        store: store,
        colModel: colModel,
        plugins: [filters],
        listeners: {
            render: {
                fn: function () {
                    store.load({});
                }
            }
        }
    });

    new Ext.Window({
        title: 'Тестовое задание',
        height: 300,
        layout: 'fit',
        closable: false,
        items: grid,
    }).show();
});
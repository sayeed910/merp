class GraphMaker {
    _cacheDom(containerId) {
        this.$container = $("#" + containerId);
        this.$monthSelector = this.$container.find('.month');
        this.$yearSelector = this.$container.find('.year');
        this.$canvas = this.$container.find('canvas');
    }

    constructor(containerId, url, type, chartOptions, method = "GET"){
        this._cacheDom(containerId);
        this.chartOptions = chartOptions;
        this.method = method;
        this.url = url;
        this.type = type;
        this._displayMonths();
        this._displayYears();
    }

    _displayYears() {
        let yearValueString = "";
        const currentYear = new Date().getFullYear();
        for (let i = 1995; i < currentYear; i++) {
            yearValueString += `<option value="${i}" >${i}</option>`
        }
        yearValueString += `<option value="${currentYear}" selected>${currentYear}</option>`;

        this.$yearSelector.html(yearValueString);
    }

    _displayMonths() {
        const months = ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'];

        let monthValueString = "";
        for (let i = 0; i < 11; i++) {
            let selected = "";
            if (i === new Date().getMonth()) {
                selected = "selected";
            }
            monthValueString += `<option value="${i}" ${selected}>${months[i]}</option>`
        }
        this.$monthSelector.html(monthValueString);
    }

    makeGraph(getLabelsAndDatas, graphLabel){
        const month = parseInt(this.$monthSelector.val()) + 1; //month is 0 indexed
        const year = parseInt(this.$yearSelector.val());
        const fromDate = `${year}-${month}-01`;
        const date = new Date(year, month, 0);
        const toDate = `${date.getFullYear()}-${month}-${date.getDate()}`;

        $.ajax({
            method: this.method,
            url: this.url,
            data: {
                date1: fromDate,
                date2: toDate
            },
            success: (response) => {
                const labelsAndDatas = getLabelsAndDatas(response);

                if (! labelsAndDatas) return;

                const myChart = new Chart(this.$canvas, {
                    type: this.type,
                    data: {
                        labels: labelsAndDatas.labels,
                        datasets: [{
                            label: graphLabel,
                            data: labelsAndDatas.datas,
                            backgroundColor: 'rgba(255,99,132,1)',
                            borderWidth: 1
                        }]
                    },
                    options: this.chartOptions
                });

            }
        });


    }
}

class BreadTable{

    constructor(tableId, options = null){
        const _defaultOptions = {
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
            }],
            "order": [[1, 'asc']]
        };
        options = options || _defaultOptions;
        this.$table = $('#'+tableId).DataTable(options);
        this.$table.on('order.dt search.dt', this.reorder.call(this)).draw();
    }

    reorder(){
        this.$table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }

    edit(){

    }

    delete(){

    }


}
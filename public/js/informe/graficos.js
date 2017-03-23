var $formRegister, $formEdit, $formDelete, $modalEliminar;

$ (function () {

    function activateTemplate(id) {
        var t = document.querySelector(id);
        return document.importNode(t.content, true);
    }

    function renderTemplateWorkFront(text, number) {

        var clone = activateTemplate('#template-work');

        clone.querySelector("[data-text]").innerHTML = text;
        clone.querySelector("[data-number]").innerHTML = number;

        $('#table-work-fronts').append(clone);
    }

    function renderTemplateCriticalRisk(text, number) {

        var clone = activateTemplate('#template-risk');

        clone.querySelector("[data-text]").innerHTML = text;
        clone.querySelector("[data-number]").innerHTML = number;

        $('#table-risk').append(clone);
    }

    function renderTemplateArea(text, number) {

        var clone = activateTemplate('#template-area');

        clone.querySelector("[data-text]").innerHTML = text;
        clone.querySelector("[data-number]").innerHTML = number;

        $('#table-area').append(clone);
    }

    function renderTemplateResponsible(text, number) {

        var clone = activateTemplate('#template-responsible');

        clone.querySelector("[data-text]").innerHTML = text;
        clone.querySelector("[data-number]").innerHTML = number;

        $('#table-responsible').append(clone);
    }

    function renderTemplateWorkFrontOpen(text, number) {

        var clone = activateTemplate('#template-work-open');

        clone.querySelector("[data-text]").innerHTML = text;
        clone.querySelector("[data-number]").innerHTML = number;

        $('#table-work-open').append(clone);
    }

    var informe_id = $('#informe-id').val();
    
    $.getJSON('../../work-fronts/graph/'+informe_id,function(response)
    {
        console.log(response[0].y);
        data2 = response;
        Highcharts.chart('container2', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Reportes según frente de trabajo'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Porcentaje',
                colorByPoint: true,
                data: response
            }]
        });
        for ( var i=0; i<=response.length; ++i) {
            if(i == response.length){
                var suma=0;
                for ( var j=0; j<response.length; ++j) {
                    suma += response[j].y;
                }
                console.log("suma"+suma);
                renderTemplateWorkFront("TOTAL", suma)
            } else {
                renderTemplateWorkFront(response[i].name, response[i].y)
            }

        }
    });

    $.getJSON('../../critical-risks/graph/'+informe_id,function(response)
    {
        Highcharts.chart('container3', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Reportes según riesgos críticos'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Porcentaje',
                colorByPoint: true,
                data: response
            }]
        });
        for ( var i=0; i<=response.length; ++i) {
            if(i == response.length){
                var suma=0;
                for ( var j=0; j<response.length; ++j) {
                    suma += response[j].y;
                }
                console.log("suma"+suma);
                renderTemplateCriticalRisk("TOTAL", suma)
            } else {
                renderTemplateCriticalRisk(response[i].name, response[i].y)
            }

        }
    });

    $.getJSON('../../areas/graph/'+informe_id,function(response)
    {
        Highcharts.chart('container4', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Reportes según áreas'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Porcentaje',
                colorByPoint: true,
                data: response
            }]
        });
        for ( var i=0; i<=response.length; ++i) {
            if(i == response.length){
                var suma=0;
                for ( var j=0; j<response.length; ++j) {
                    suma += response[j].y;
                }
                console.log("suma"+suma);
                renderTemplateArea("TOTAL", suma)
            } else {
                renderTemplateArea(response[i].name, response[i].y)
            }

        }
    });

    $.getJSON('../../responsible/graph/'+informe_id,function(response)
    {
        Highcharts.chart('container6', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Reportes según responsables'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Porcentaje',
                colorByPoint: true,
                data: response
            }]
        });
        for ( var i=0; i<=response.length; ++i) {
            if(i == response.length){
                var suma=0;
                for ( var j=0; j<response.length; ++j) {
                    suma += response[j].y;
                }
                console.log("suma"+suma);
                renderTemplateResponsible("TOTAL", suma)
            } else {
                renderTemplateResponsible(response[i].name, response[i].y)
            }

        }
    });

    $.getJSON('../../work-fronts-opens/graph/'+informe_id,function(response)
    {
        console.log(response[0].y);
        data2 = response;
        Highcharts.chart('container7', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Reportes abiertos por frente de trabajo'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Porcentaje',
                colorByPoint: true,
                data: response
            }]
        });
        for ( var i=0; i<=response.length; ++i) {
            if(i == response.length){
                var suma=0;
                for ( var j=0; j<response.length; ++j) {
                    suma += response[j].y;
                }
                console.log("suma"+suma);
                renderTemplateWorkFrontOpen("TOTAL", suma)
            } else {
                renderTemplateWorkFrontOpen(response[i].name, response[i].y)
            }

        }
    });

    


});



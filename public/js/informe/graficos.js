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

    function renderTemplateResponsibleOpen(text, number) {
        var clone = activateTemplate('#template-responsible-open');

        clone.querySelector("[data-text]").innerHTML = text;
        clone.querySelector("[data-number]").innerHTML = number;

        $('#table-responsible-open').append(clone);
    }

    var informe_id = $('#informe-id').val();
    
    $.getJSON('../../work-fronts/graph/'+informe_id,function(response)
    {
        Highcharts.chart('container2', {
            chart: {
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
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
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
        var sum = 0;
        for (var i=0; i<response.length; ++i) {
            sum += response[i].y;
            renderTemplateWorkFront(response[i].name, response[i].y)
        }
        renderTemplateWorkFront("TOTAL", sum);
    });

    $.getJSON('../../critical-risks/graph/'+informe_id,function(response)
    {
        // console.log(response);
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
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
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
        var sum = 0;
        for (var i=0; i<response.length; ++i) {
            renderTemplateCriticalRisk(response[i].name, response[i].y)
            sum += response[i].y;
        }
        renderTemplateCriticalRisk("TOTAL", sum)
    });

    $.getJSON('../../areas/graph/'+informe_id,function(response)
    {
        // console.log(response);
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
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
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

        var sum = 0;
        for (var i=0; i<response.length; ++i) {
            renderTemplateArea(response[i].name, response[i].y);
            sum += response[i].y;
        }
        renderTemplateArea("TOTAL", sum);
    });

    $.getJSON('../../responsible/graph/'+informe_id,function(response)
    {
        Highcharts.chart('container6', {
            chart: {
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
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
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
        var sum = 0;
        for (var i=0; i<response.length; ++i) {
            renderTemplateResponsible(response[i].name, response[i].y)
            sum += response[i].y;
        }
        renderTemplateResponsible("TOTAL", sum);
    });

    $.getJSON('../../work-fronts-opens/graph/'+informe_id,function(response)
    {
        Highcharts.chart('container7', {
            chart: {
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
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
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
        var sum = 0;
        for (var i=0; i<response.length; ++i) {
            renderTemplateWorkFrontOpen(response[i].name, response[i].y)
            sum += response[i].y;
        }
        renderTemplateWorkFrontOpen("TOTAL", sum)
    });

    $.getJSON('../../responsible-opens/graph/'+informe_id,function(response)
    {
        Highcharts.chart('container8', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Reportes abiertos según responsables'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
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
        var sum = 0;
        for (var i=0; i<response.length; ++i) {
            renderTemplateResponsibleOpen(response[i].name, response[i].y)
            sum += response[i].y;
        }
        renderTemplateResponsibleOpen("TOTAL", sum)
    });

});

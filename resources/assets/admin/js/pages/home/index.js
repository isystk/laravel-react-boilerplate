import Highcharts from 'highcharts';

$(function () {
  const $el = $('#sales-chart');
  if ($el.length === 0) return;

  const salesData = $el.data('sales');
  console.log({salesData})

  Highcharts.chart('sales-chart', {
    chart: { type: 'line' },
    title: { text: null },
    xAxis: {
      categories: salesData.map((item) => item.year_month),
    },
    yAxis: {
      title: { text: '売上金額 (円)' },
    },
    series: [
      {
        name: '売上',
        data: salesData.map((item) => item.amount),
      },
    ],
    credits: { enabled: false },
  });
});

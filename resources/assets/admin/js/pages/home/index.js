import Highcharts from 'highcharts';

$(function () {
  // 売上推移チャート
  const $salesEl = $('#sales-chart');
  if ($salesEl.length > 0) {
    const salesData = $salesEl.data('sales');

    Highcharts.chart('sales-chart', {
      chart: { type: 'line' },
      title: { text: null },
      xAxis: {
        categories: salesData.map(item => item.year_month),
      },
      yAxis: {
        title: { text: '売上金額 (円)' },
      },
      series: [
        {
          name: '売上',
          data: salesData.map(item => item.amount),
        },
      ],
      credits: { enabled: false },
    });
  }

  // 月別新規ユーザー推移チャート
  const $usersEl = $('#users-chart');
  if ($usersEl.length > 0) {
    const usersData = $usersEl.data('users');

    Highcharts.chart('users-chart', {
      chart: { type: 'column' },
      title: { text: null },
      xAxis: {
        categories: usersData.map(item => item.year_month),
      },
      yAxis: {
        title: { text: '新規ユーザー数 (人)' },
        allowDecimals: false,
      },
      series: [
        {
          name: '新規ユーザー',
          data: usersData.map(item => item.count),
        },
      ],
      credits: { enabled: false },
    });
  }
});

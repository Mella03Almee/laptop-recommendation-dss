Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// FUNGSI LOGIS: Membuat warna kontras otomatis sebanyak jumlah data kriteria
function generatePieColors(count) {
    var colors = [];
    for (var i = 0; i < count; i++) {
        // Membagi sudut warna 360 derajat secara merata berdasarkan jumlah kriteria
        var hue = (i * (360 / count)) % 360;
        colors.push('hsl(' + hue + ', 65%, 50%)');
    }
    return colors;
}

// Otomatis membuat warna sebanyak jumlah kriteria yang ada di sistem
var dynamicPieColors = generatePieColors(dataKriteriaNama.length);

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: dataKriteriaNama,
    datasets: [{
      data: dataKriteriaBobot,
      backgroundColor: dynamicPieColors, // Warna otomatis dinamis sesuai jumlah kriteria
      borderColor: "#fff", 
      borderWidth: 2, 
    }],
  },
  options: {
    legend: {
      display: true,
      position: 'right', 
    },
    tooltips: {
      callbacks: {
        label: function (tooltipItem, data) {
          return data.labels[tooltipItem.index] + ': ' + data.datasets[0].data[tooltipItem.index];
        }
      }
    }
  }
});
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

let nilaiMaxY = 1 / dataNilaiV.length + 0.1;

// FUNGSI LOGIS: Membuat warna cerah otomatis sebanyak jumlah data laptop
function generateDynamicColors(count) {
    var colors = [];
    for (var i = 0; i < count; i++) {
        // Menggunakan konsep HSL agar warna yang dihasilkan selalu cerah (tidak kusam/abu-abu)
        var hue = (i * (360 / count)) % 360;
        colors.push('hsl(' + hue + ', 70%, 55%)');
    }
    return colors;
}

// Otomatis membuat warna sebanyak total data nama alternatif laptop yang dikirim dari Blade
var dynamicBarColors = generateDynamicColors(dataNamaAlternatif.length);

// Bar Chart Example
var ctx = document.getElementById("myBarChart");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: dataNamaAlternatif,
    datasets: [{
      label: "Nilai V",
      backgroundColor: dynamicBarColors, // Warna otomatis dinamis
      borderColor: dynamicBarColors,
      data: dataNilaiV,
    }],     
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'Laptop'
        },
        gridLines: {
          color: "#eaecf4", 
          display: true
        },
        ticks: {
          maxTicksLimit: dataNamaAlternatif.length
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: nilaiMaxY,
          maxTicksLimit: dataNilaiV.length + 1
        },
        gridLines: {
          color: "#eaecf4",
          display: true
        }
      }],
    },
    legend: {
      display: true
    }
  }
});
import Chart from 'chart.js'

<template>
   <div class="container">
        Donut Model
        <canvas id="myDonut" style="width: 20px; height: 10px"></canvas>
        Bar Chart        
        <canvas id="myChart" style="width: 20px; height: 10px"></canvas>
           
    </div>
</template>

<script>
    export default {
        props:['labels' , 'values'],
        
        mounted() {
           new Chart(document.getElementById("myDonut"), {
                type: 'doughnut',
                data: {
                  labels: this.labels,
                  datasets: [
                    {
                      label: "Sentiment",
                      backgroundColor: ["#00cd00", "#000","#FF0000"],
                      data: [this.values.pos , this.values.neutral, this.values.neg]
                    }
                  ]
                },
                options: {
                  legend: { display: false },
                  title: {
                    display: true,
                    text: 'Sentiment Analysis for the Sample Texts Pie Chart'
                  },
                  segmentShowStroke : true,
                  segmentStrokeColor : "#fff",
                  segmentStrokeWidth : 2,
                  percentageInnerCutout : 50,
                  animationSteps : 100,
                  animationEasing : "easeOutBounce",
                  animateRotate : true,
                  animateScale : false,
                  responsive: true,
                  maintainAspectRatio: true,
                  showScale: true,
                  animateScale: true
                }
            });
           new Chart(document.getElementById("myChart"), {
                type: 'bar',
                data: {
                  labels: this.labels,
                  datasets: [
                    {
                      label: "Sentiment",
                      backgroundColor: ["#00cd00", "#000","#FF0000"],
                      data: [this.values.pos , this.values.neutral, this.values.neg]
                    }
                  ]
                },
                options: {
                  legend: { display: false },
                  title: {
                    display: true,
                    text: 'Sentiment Analysis for the Sample Texts Bar Chart'
                  }
                }
            });

            
        }
    }
</script>

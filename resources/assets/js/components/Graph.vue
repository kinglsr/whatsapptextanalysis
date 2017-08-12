import Chart from 'chart.js'

<template>
   <div class="container">        
        <canvas id='myChart' width='400' height='300'></canvas>   
    </div>
</template>

<script>
    export default {
        props:['labels' , 'values'],
        
        mounted() {
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
                    text: 'Sentiment Analysis for the Selected Texts'
                  }
                }
            });
        }
    }
</script>

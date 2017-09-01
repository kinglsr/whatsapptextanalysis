import Chart from 'chart.js'

<template>
   <div class="container">        
        <canvas id="myChart" style="width: 20px; height: 10px"></canvas>   
    </div>
</template>

<script>
    export default {
        props:['dates' , 'positive' , 'negative' , 'neutral' , 'person'],        

        mounted() {
           new Chart(document.getElementById("myChart"), {
                type: 'bar',
                data : {
                    labels: this.dates,
                    datasets: [
                        {
                            label: "Positive",
                            backgroundColor: "green",
                            data: this.positive
                        },
                        {
                            label: "Negative",
                            backgroundColor: "red",
                            data: this.negative
                        },
                        {
                            label: "Neutral",
                            backgroundColor: "blue",
                            data: this.neutral
                        }
                    ]
                },
                options: {
                  legend: { display: false },
                  title: {
                    display: true,
                    text: 'Sentiment Analysis with ' + this.person.name
                  }
                }
            });
        }
    }
</script>

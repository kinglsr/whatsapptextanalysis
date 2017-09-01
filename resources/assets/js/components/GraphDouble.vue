import Chart from 'chart.js'

<template>
   <div class="container">        
        <canvas id="myChartPos" style="width: 20px; height: 10px"></canvas> 
        <canvas id="myChartNeg" style="width: 20px; height: 10px"></canvas>
        <canvas id="myChartNeutral" style="width: 20px; height: 10px"></canvas>
        <canvas id="myChartPerson1" style="width: 20px; height: 10px"></canvas> 
        <canvas id="myChartPerson2" style="width: 20px; height: 10px"></canvas>    
    </div>
</template>
<script>
    export default {
        props:['dates' , 'positive1' , 'positive2' , 'negative1' , 'negative2' , 'neutral1' , 'neutral2' ,'person1', 'person2'],   

        mounted() {
           new Chart(document.getElementById("myChartPos"), {
                type: 'bar',
                data : {
                    labels: this.dates,
                    datasets: [
                        {
                            label: this.person1.name,
                            backgroundColor: "pink",
                            data: this.positive1
                        },
                        {
                            label: this.person2.name,
                            backgroundColor: "yellow",
                            data: this.positive2
                        }
                    ]
                },
                options: {
                  legend: { display: false },
                  title: {
                    display: true,
                    text: 'Positive Sentiment Analysis Between ' + this.person1.name + " and " + this.person2.name
                  }
                }
            });
            new Chart(document.getElementById("myChartNeg"), {
                type: 'bar',
                data : {
                    labels: this.dates,
                    datasets: [
                        {
                            label: this.person1.name,
                            backgroundColor: "brown",
                            data: this.negative1
                        },
                        {
                            label: this.person2.name,
                            backgroundColor: "pale",
                            data: this.negative2
                        }
                    ]
                },
                options: {
                  legend: { display: false },
                  title: {
                    display: true,
                    text: 'Negative Sentiment Analysis Between ' + this.person1.name + " and " + this.person2.name
                  }
                }
            });
            new Chart(document.getElementById("myChartNeutral"), {
                type: 'bar',
                data : {
                    labels: this.dates,
                    datasets: [
                        {
                            label: this.person1.name,
                            backgroundColor: "black",
                            data: this.neutral1
                        },
                        {
                            label: this.person2.name,
                            backgroundColor: "orange",
                            data: this.neutral2
                        }
                    ]
                },
                options: {
                  legend: { display: false },
                  title: {
                    display: true,
                    text: 'Neutral Sentiment Analysis Between ' + this.person1.name + " and " + this.person2.name
                  }
                }
            });
           new Chart(document.getElementById("myChartPerson1"), {
                type: 'bar',
                data : {
                    labels: this.dates,
                    datasets: [
                        {
                            label: "Positive",
                            backgroundColor: "green",
                            data: this.positive1
                        },
                        {
                            label: "Negative",
                            backgroundColor: "red",
                            data: this.negative1
                        },
                        {
                            label: "Neutral",
                            backgroundColor: "blue",
                            data: this.neutral1
                        }
                    ]
                },
                options: {
                  legend: { display: false },
                  title: {
                    display: true,
                    text: 'Sentiment Analysis with ' + this.person1.name
                  }
                }
            });
            new Chart(document.getElementById("myChartPerson2"), {
                type: 'bar',
                data : {
                    labels: this.dates,
                    datasets: [
                        {
                            label: "Positive",
                            backgroundColor: "green",
                            data: this.positive2
                        },
                        {
                            label: "Negative",
                            backgroundColor: "red",
                            data: this.negative2
                        },
                        {
                            label: "Neutral",
                            backgroundColor: "blue",
                            data: this.neutral2
                        }
                    ]
                },
                options: {
                  legend: { display: false },
                  title: {
                    display: true,
                    text: 'Sentiment Analysis with ' + this.person2.name
                  }
                }
            });
        }
    }    
</script>




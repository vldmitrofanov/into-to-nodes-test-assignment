export default {
    data(){
        return{
            months : [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
                ]
        }
    },
    methods: {
      prettyTime(time) {
        const arr = time.split(":");
        const dt = arr[0] >= 12?'PM':'AM'
        const h = arr[0] >12? arr[0]-12:arr[0]
        return `${h}:${arr[1]} ${dt}`
      },
      prettyDate(date){
        const arr = date.split("-");
        return `${Number(arr[2])} ${this.months[Number(arr[1]-1)]} ${arr[0]}`
      }
    }
  };
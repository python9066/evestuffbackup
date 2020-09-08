import CountupTimerComponent from './countup.vue'

const MILLISECONDS_SECOND = 1000;
const MILLISECONDS_MINUTE = 60 * MILLISECONDS_SECOND;
const MILLISECONDS_HOUR = 60 * MILLISECONDS_MINUTE;
const MILLISECONDS_DAY = 24 * MILLISECONDS_HOUR;

const VueCountUptimer = {
  install(Vue, options) {
    Vue.component('VueCountUptimer', {
      mixins: [CountupTimerComponent],
      props: {
        startLabel: {
          type: String,
          default: ''
        },
        endLabel: {
          type: String,
          default: ''
        },
        labelPosition: {
          type: String,
          default: 'begin'
        },
        interval: {
          type: Number,
          default: function () {
            return 1000
          }
        },
        leadingZero: {
          type: Boolean,
          default: function () {
            return true
          }
        },
        showZero: {
          type: Boolean,
          default: function () {
            return true
          }
        },
        startTime: {
          type: Number|String
        },

        dayTxt: {
          type: String|null,
          default: function () {
            return ':'
          }
        },
        hourTxt: {
          type: String|null,
          default: function () {
            return ':'
          }
        },
        minutesTxt: {
          type: String|null,
          default: function () {
            return ':'
          }
        },
        secondsTxt: {
          type: String,
          default: function () {
            return ':'
          }
        },
        secondsFixed: {
          type: Boolean,
          default: function () {
            return false
          }
        }
      },
      data() {
        return {
          tips: true,
          current: '',
          count: 0, // 现在倒计时剩余毫秒数
          counting: false, // 是否已经在倒计
          showDay: true,
          showHour: true,
          showMinute: true
        }
      },
      computed: {
        /**
         * Remaining days.
         * @returns {number}
         */
        days() {
          return this.preprocess(Math.floor(this.count / MILLISECONDS_DAY));
        },

        /**
         * Remaining hours.
         * @returns {number}
         */
        hours() {
          let hours = Math.floor((this.count % MILLISECONDS_DAY) / MILLISECONDS_HOUR);
          // If disabled day display convert all days into hours
          if (!this.dayTxt) {
            hours = hours + Math.floor(this.count / MILLISECONDS_DAY) * 24;
          }
          return this.preprocess(hours)
        },

        /**
         * Remaining minutes.
         * @returns {number}
         */
        minutes() {
          let minutes = Math.floor((this.count % MILLISECONDS_HOUR) / MILLISECONDS_MINUTE);
          console.log(minutes);

          if (!this.hourTxt) {
            let days = Math.floor(this.count / MILLISECONDS_DAY)
            let hours = Math.floor((this.count % MILLISECONDS_DAY) / MILLISECONDS_HOUR)
            // Disabled hours but shows days
            // Convert all hours into minutes
            minutes = minutes + hours * 60
            // Disabled both hours and shows
            // Convert all days and hours into minutes
            if (!this.dayTxt) {
              minutes = minutes + (days * 24 * 60)
            }
          }

          return this.preprocess(minutes)
        },

        /**
         * Remaining seconds.
         * @returns {number}
         */
        seconds() {
          const { interval } = this;
          let seconds = (this.count % MILLISECONDS_MINUTE) / MILLISECONDS_SECOND;

          if (!this.minutesTxt) {
            let minutes = Math.floor((this.count % MILLISECONDS_HOUR) / MILLISECONDS_MINUTE)
            seconds = seconds + minutes * 60
            if (!this.hourTxt) {
              let hours = Math.floor((this.count % MILLISECONDS_DAY) / MILLISECONDS_HOUR)
              seconds = seconds + hours * 60 * 60
              if (!this.dayTxt) {
                let days = Math.floor(this.count / MILLISECONDS_DAY)
                seconds = seconds + days * 24 * 60 * 60
              }
            }
          }

          if (interval < 10) {
            return this.preprocess(parseFloat(seconds.toFixed(3)));
          } else if (interval >= 10 && interval < 100) {
            return this.preprocess(parseFloat(seconds.toFixed(2)));
          } else if (interval >= 100 && interval < 1000) {
            return this.preprocess(parseFloat(seconds.toFixed(1)));
          }

          return this.preprocess(Math.floor(seconds));
        },

        /**
         * current event status
         * 当前活动状态
         * @returns {number}
         */
        status() {

          // Current time is smaller than event start time
          // 当前时间小于开始时间 活动尚未开始
          if (this.current > new Date(this.formatTime(this.startTime)).getTime()) {
            return 1;
          }

        },

      },
      methods: {
        /**
         * Initial countdown
         * 初始化倒计时
         */
        init() {
          if (!this.dayTxt) {
            this.showDay = false
          }

          if (!this.hourTxt) {
            this.showHour = false
          }

          if (!this.minutesTxt) {
            this.showMinute = false
          }
          // Formating time - 格式化时间格式
          this.stop();
          this.$set(this, 'current', new Date().getTime())
          const startCount = this.current -new Date(this.formatTime(this.startTime)).getTime() ;

          const { status } = this;



          if (status === 1) {
            this.$set(this, 'tips', true);
            this.count = Math.max(0, startCount);
          }



          this.$nextTick(() => {
            this.start();
          });
        },

        /**
         * Start countdown
         * 开始倒计时
         */
        start () {
          if (this.counting) {
            return;
          }

          this.counting = true;
          this.next();
        },

        /**
         * Next count down queue
         * 进入下一个倒计时队列
         */
        next() {
          this.timeout = setTimeout(this.step.bind(this), this.interval);
        },

        /**
         * Calculate remaining milliseconds
         * 重新计算剩余时间 - 毫秒
         */
        step() {
          if (!this.counting) {
            return;
          }

          if (this.count > this.interval) {
            // Update 00 display status
            if (!this.showZero) {
              if (Number(this.days) === 0) {
                this.showDay = false
              }
              if (!this.showDay && Number(this.hours) === 0) {
                this.showHour = false
              }
              if (!this.showHour && Number(this.minutes) === 0) {
                this.showMinute = false
              }
            }
            console.log(minutes," - ", seconds);
            this.count += this.interval;
            // this.$emit('fofo',this.count);
            if(this.count > 1799000 && this.count < 1801000  || this.count > 3599000 && this.count < 3601000 || this.count > 5399000 && this.count < 5401000 || this.count > 7199000 && this.count < 7201000 || this.count > 8999000 && this.count < 9001000 || this.count > 10799000 && this.count < 10801000){
            this.$emit('timecheck', this.count);
            }
            this.next();
          } else {
            this.count = 0;
            this.init();
          }
        },
        /**
         * Stop the countdown
         * 停止倒计时
         */
        stop() {
          this.counting = false;
          clearTimeout(this.timeout);
          this.timeout = undefined;
        },

        start_message () {
          this.$set(this, 'tips', false);
          this.$emit('start_callback', this.status);
        },



        /**
         * Format time into unique format
         * @param time
         * @returns {*}
         */
        formatTime(time) {
          if (typeof time === 'number') {
            if (time.toString().length === 10) {
              return time * 1000
            } else {
              return time
            }
          } else {
            return time
          }
        },

        /**
         * Filling zeros
         * @returns {string}
         */
        preprocess(value) {
          return (this.leadingZero && value < 10 ? `0${value}` : value);
        },

        /**
         * Update the count.
         * @private
         */
        update() {
          if (this.counting) {
            // Formating time - 格式化时间格式
            this.$set(this, 'current', new Date().getTime())
            const startCount = this.current - new Date(this.formatTime(this.startTime)).getTime() ;

            const { status } = this;

            if (status === 0) {
              this.count = 0;
              this.stop();
              this.end_message();
              return;
            }

            if (status === 1) {

              this.$set(this, 'tips', true);
              this.count = Math.max(0, startCount);
            }

            if (status === 2) {
              this.$set(this, 'tips', false);
              this.$emit('start_callback', this.status);
              this.count = Math.max(0, endCount);
            }
          }
        }
      },

      watch: {
        startTime() {
          this.init();
        },

        endTime() {
          this.init();
        },
      },

      created() {
        this.init();
      },

      mounted() {
        window.addEventListener('focus', (this.onFocus = this.update.bind(this)));
      },

      beforeDestroy() {
        window.removeEventListener('focus', this.onFocus);
        clearTimeout(this.timeout);
      }
    })
  }
}

if (typeof window !== 'undefined' && window.Vue) {
  window.Vue.use(VueCountUptimer)
}

export default VueCountUptimer

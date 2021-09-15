const Wizard = {
	data() {
		return {
			screen: 0,
			currentPage: "welcome",
			userType: 'normal',
			steps: [
				{
					key: "welcome",
					name: "Welcome",
					isComplete: false,
				},
				{
					key: "widgets",
					name: "Widgets",
					isComplete: false,
				},
				{
					key: "features",
					name: "Features",
					isComplete: false,
				},
				{
					key: "bepro",
					name: "Be a pro!",
					isComplete: false,
				},
				{
					key: "contribute",
					name: "Contribute",
					isComplete: false,
				},
				{
					key: "congrats",
					name: "Congrats",
					isComplete: false,
				},
			],

			widgetList: [],
			disabledWidgets: [],

			settings: {
				welcome: {
					userType: null,
				},
				widgets: [],
				features: null,
				contribute: false,
				all: [],
				checkedWidgets: [],
			},

			widgetMore: true,
		};
	},
	mounted() {
		this.getCurrentPage();
		this.fetchWidgetData();
	},
	methods: {
		async fetchWidgetData() {
			const url = window.HappyWizard.apiBase+"/widgets/all/";

			await fetch(url, {
				method: "GET",
				headers: { "X-WP-Nonce": window.HappyWizard.nonce },
			})
				.then((response) => response.json())
				.then((data) => {
					if (data) {
						this.widgetList = data.all;
						this.disabledWidgets = data.disabled;
					}
				})
				.catch((error) => {
					console.error("Error:", error);
				});
		},

		async fetchPreset(userType) {
			const url = window.HappyWizard.apiBase+"/wizard/preset/"+userType;

			await fetch(url, {
				method: "GET",
				headers: { "X-WP-Nonce": window.HappyWizard.nonce },
			})
				.then((response) => response.json())
				.then((data) => {
					if (data) {
						console.log(data)
					}
				})
				.catch((error) => {
					console.error("Error:", error);
				});
		},

		setUserType(type){
			this.userType = type

			this.fetchPreset(type)
		},
		setTab(screen) {
			this.currentPage = screen;
			this.screen = screen;
		},
		revealWidgetList() {
			this.widgetMore = false;
		},
		getCurrentPage() {
			for (let elem of this.steps) {
				if (elem.isComplete == false) {
					this.currentPage = elem.key;
					break;
				}
			}
			return this.currentPage;
		},

		goNext(screen) {
			this.setTab(screen);
		},

		allAdd(key) {
			// this.settings.all.filter(f => f !== key).push(key)
			const modified = this.widgetList[key];

			const localThis = this;
			Object.keys(modified).forEach(function (item) {
				modified[item].is_active = true;
				localThis.isActive(modified[item].slug, false);
			});

			if (this.settings.all.indexOf(key) === -1) {
				this.settings.all.push(key);
			}
			return modified;
		},

		allRemove(key) {
			const modified = this.widgetList[key];
			const localThis = this;

			Object.keys(modified).forEach(function (item) {
				modified[item].is_active = false;
				localThis.isActive(modified[item].slug, true);
			});

			this.settings.all = this.settings.all.filter(function (
				value,
				index,
				arr
			) {
				return value != key;
			});
			return modified;

			//console.log(JSON.stringify(this.settings.all));
		},

		isActive(key, stat) {
			console.log(key + ":" + stat);
			if (stat === true) {
				console.log("hit true");
				if (this.disabledWidgets.indexOf(key) === -1) {
					this.disabledWidgets.push(key);
				}
			} else {
				console.log("hit false");
				this.disabledWidgets = this.disabledWidgets.filter(function (
					value,
					index,
					arr
				) {
					return value != key;
				});
			}
			console.log(this.disabledWidgets);
		},

		makeTitle(slug) {
			var title = slug.replace(/-/g, " ").replace("and", "&");
			return title.charAt(0).toUpperCase() + title.slice(1);
		},
	},

	watch: {
		"settings.checkedWidgets": function (val) {
			console.log(this.settings.checkedWidgets);
			//this.log();
		},

		"settings.all": function (val) {
			//console.log(this.settings.all);

			console.log(JSON.stringify(this.settings.all));
			//this.isWidgetActive();
		},

		currentPage : function(val){
			console.log(val);
		}
	},

	computed: {},
};
const app = Vue.createApp(Wizard);
app.config.globalProperties.window = window;

app.component("ha-step", {
	props: {
		active: String,
		complete: Boolean,
		step: String,
		title: String,
		index: Number,
	},
	emits: ["setTab"],
	computed: {
		isActive() {
			return this.active == this.step ? true : false;
		},
	},
	template: `<div class="ha-stepper__step" :class="{ 'is-complete': this.complete, 'is-active': this.isActive }">
	<button class="ha-stepper__step-label-wrapper" @click="$emit('setTab',step)">
		<div class="ha-stepper__step-icon">
			<span class="ha-stepper__step-number">{{index}}</span>
			<svg width="15" height="11" viewBox="0 0 15 11" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M5.09467 10.784L0.219661 5.98988C-0.0732203 5.70186 -0.0732203 5.23487 0.219661 4.94682L1.2803 3.90377C1.57318 3.61572 2.04808 3.61572 2.34096 3.90377L5.625 7.13326L12.659 0.216014C12.9519 -0.0720048 13.4268 -0.0720048 13.7197 0.216014L14.7803 1.25907C15.0732 1.54709 15.0732 2.01408 14.7803 2.30213L6.15533 10.784C5.86242 11.072 5.38755 11.072 5.09467 10.784Z" fill="white"/>
			</svg>
		</div>
		<div class="ha-stepper__step-text">
			<span class="ha-stepper__step-label">{{title}}</span>
		</div>
	</button>
</div>
<div class="ha-stepper__step-divider">
<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M14.2218 4.80762C13.8313 4.4171 13.1981 4.4171 12.8076 4.80762C12.4171 5.19815 12.4171 5.83131 12.8076 6.22184L14.2218 4.80762ZM18.4853 10.4853L19.1924 11.1924L19.8995 10.4853L19.1924 9.77818L18.4853 10.4853ZM12.8076 14.7487C12.4171 15.1393 12.4171 15.7724 12.8076 16.163C13.1981 16.5535 13.8313 16.5535 14.2218 16.163L12.8076 14.7487ZM7.19238 4.80762C6.80186 4.4171 6.16869 4.4171 5.77817 4.80762C5.38764 5.19814 5.38764 5.83131 5.77817 6.22183L7.19238 4.80762ZM11.4558 10.4853L12.1629 11.1924L12.87 10.4853L12.1629 9.77818L11.4558 10.4853ZM5.77817 14.7487C5.38764 15.1393 5.38764 15.7724 5.77817 16.163C6.16869 16.5535 6.80186 16.5535 7.19238 16.163L5.77817 14.7487ZM12.8076 6.22184L17.7782 11.1924L19.1924 9.77818L14.2218 4.80762L12.8076 6.22184ZM17.7782 9.77818L12.8076 14.7487L14.2218 16.163L19.1924 11.1924L17.7782 9.77818ZM5.77817 6.22183L10.7487 11.1924L12.1629 9.77818L7.19238 4.80762L5.77817 6.22183ZM10.7487 9.77818L5.77817 14.7487L7.19238 16.163L12.1629 11.1924L10.7487 9.77818Z" fill="currentColor"/>
</svg>
</div>`,
});

app.component("ha-nav", {
	props: { prev: String, next: String, done: String },
	emits: ["setTab"],
	template: `<div class="ha-setup-wizard__nav">
        <button class="ha-setup-wizard__nav_prev" v-if="prev" @click="$emit('setTab',prev)">
            <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 3.33333H2.55333L4.94 0.94L4 0L0 4L4 8L4.94 7.06L2.55333 4.66667H12V3.33333Z" fill="black"/>
            </svg>
            <span>Back</span>
        </button>
        <button class="ha-setup-wizard__nav_next" v-if="next" @click="$emit('setTab',next)"><span>Next</span></button>
        <button class="ha-setup-wizard__nav_done" v-if="done" @click="$emit('setTab','done')"><span>Done</span></button>
    </div>
	`,
});
app.mount("#ha-setup-wizard");

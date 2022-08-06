
<!-- eslint-disable -->
/* eslint-disable */
<style scoped>
.nav-link {
  text-decoration: none;
}
.nav-link:first-child {
  margin-right: 5px;
}
.nav-link:last-child {
  margin-left: 5px;
}
</style>
<template>
  <div class="br3 ba b--gray-monica bg-white mb3">
    <notifications
      group="main"
      position="bottom right"
      width="400"
    />

    <div class="pa4">
      <!-- Step 1 -->
      <form
        id="form"
        action="register"
        method="post"
      >
        <ul class="nav nav-pills nav-fill">
          <li
            v-for="(item,index) in steps"
            :class="{
              'nav-item': true,
            }"
            :key="index"
          >
            <a
              href="#"
              :class="{
            'nav-link': true,
  'active': item.value === step,
            disabled: getIsStepDisabled(item.value)
          }"
              @click.prevent="changeStep(item.value)"
            ><i
                v-if="!item.isInvalid"
                class="fa fa-check"
              ></i>&nbsp;{{ item.label }}</a>
          </li>
        </ul>
        <div
          v-if="activeTab === 'step0'"
          class="card px-3 mt-3 signup-box"
        >
          <div class="form-group">
            <h2>Step 1: Select Your Email Username</h2>
            <label for="mailbox_key">{{ i18n.auth.register_mailbox_key }}</label>
            <div class="row">
              <div
                class="col col-md-7"
                style="padding-right: 5px"
              >
                <input
                  v-model="form.mailbox_key"
                  type="text"
                  class="form-control"
                  id="mailbox_key"
                  name="mailbox_key"
                  :placeholder="i18n.auth.register_mailbox_key_example"
                  required
                  autocomplete="mailbox_key"
                  autofocus
                  @blur="checkMailboxKey"
                  @keydown.enter="checkMailboxKey"
                />
              </div>
              <div
                class="col col-md-5 d-flex align-center"
                style="align-items:center; padding-left: 5px"
              >
                <span><strong>@ {{ config.domain }}</strong></span>
              </div>
            </div>
            <div class="col col-12">
              <p
                v-if="errors.mailbox_key != undefined"
                class="form-error-message"
              >Username not available. Please enter another</p>
            </div>
            <div class="row">
              <div
                class="col col-md-5 d-flex align-center"
                style="align-items:center; padding-left: 5px"
                @click.prevent="next"
              >
                <button
                  :disabled="getIsStepDisabled(1)"
                  :class="{ 'btn btn-info': true, disabled: getIsStepDisabled(1) }"
                >Next</button>
              </div>
            </div>
          </div>
        </div>

        <div
          v-if="activeTab === 'step1'"
          class="card px-3 mt-3 signup-box"
        >
          <div class="form-group">
            <h2>Step 2: Select a Charity to Donate to</h2>
            <p
              class="info"
              v-html="i18n.auth.register_charity_disclaimer"
            ></p>
            <div class="form-group card">
              <label
                class="card-header"
                for="charity_preference"
              >{{ i18n.auth.register_charity_preference }}</label>

              <div
                class="card-body"
                id="charity-preference"
              >
                <div
                  v-for="(id, charity) in charities"
                  :key="id"
                  class="form-check"
                  style="padding: 8px 8px; cursor: pointer"
                >
                  <label
                    class="form-check-label"
                    style="cursor: pointer"
                    :for="`preference-${id}`"
                  >
                    <input
                      v-model="form.charity_preference"
                      class="form-check-input"
                      type="radio"
                      name="charity_preference"
                      :value="id"
                      :id="`preference-${id}`"
                      :selected="parseInt(old.charity_preference) == parseInt(id)"
                      @click="validateStep(1)"
                    />
                    {{ charity }}
                  </label>
                </div>
              </div>
            </div>
            <div
              class="col col-md-5 d-flex align-center"
              style="align-items:center; padding-left: 5px"
              @click.prevent="next"
            >
              <button
                :disabled="getIsStepDisabled(2)"
                :class="{ 'btn btn-info': true, disabled: getIsStepDisabled(2) }"
              >Next</button>
            </div>
          </div>
        </div>

        <div
          v-if="activeTab === 'step2'"
          class="card px-3 mt-3 signup-box"
        >
          <div class="row">
            <div class="col col-12">
              <h2>Step 3: Enter Your Account Details</h2>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label for="first_name">{{ i18n.auth.register_firstname }}</label>
                <input
                  v-model="form.first_name"
                  type="text"
                  class="form-control"
                  id="first_name"
                  name="first_name"
                  :placeholder="i18n.auth.register_firstname_example"
                  required
                  autocomplete="given-name"
                  @blur="validateStep(2)"
                />
                <p
                  v-if="errors.first_name != undefined"
                  class="form-error-message"
                >{{ errors.first_name[0] }}</p>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label for="last_name">{{ i18n.auth.register_lastname }}</label>
                <input
                  v-model="form.last_name"
                  type="text"
                  class="form-control"
                  id="last_name"
                  name="last_name"
                  :placeholder="i18n.auth.register_lastname_example"
                  required
                  autocomplete="family-name"
                  @blur="validateStep(2)"
                />
                <p
                  v-if="errors.last_name != undefined"
                  class="form-error-message"
                >{{ errors.last_name[0] }}</p>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="email">{{ i18n.auth.register_email }}</label>
            <input
              v-model="form.email"
              type="email"
              class="form-control"
              id="email"
              name="email"
              :placeholder="i18n.auth.register_email_example"
              required
              autocomplete="email"
              autofocus
              @blur="recordForAbandonedCart"
              @input="validateStep(2)"
            />
            <p
              v-if="errors.email != undefined"
              class="form-error-message"
            >{{ errors.email[0] }}</p>
          </div>

          <div class="form-group">
            <label for="dob">{{ i18n.auth.register_dob }}</label>
            <input
              v-model="form.dob"
              type="date"
              class="form-control"
              id="dob"
              name="dob"
              :placeholder="i18n.auth.register_dob_example"
              required
              autocomplete="dob"
              @blur="validateStep(2)"
            />
            <p
              v-if="errors.dob != undefined"
              class="form-error-message"
            >{{ errors.dob[0] }}</p>
          </div>

          <div class="form-group">
            <label for="password">{{ i18n.auth.register_password }}</label>
            <input
              v-model="form.password"
              type="password"
              class="form-control"
              id="password"
              name="password"
              :placeholder="i18n.auth.register_password_example"
              required
              autocomplete="password"
              @blur="validateStep(2)"
            />
            <p
              v-if="errors.password != undefined"
              class="form-error-message"
            >{{ errors.password[0] }}</p>
          </div>

          <div class="form-group">
            <label for="password_confirmation">{{ i18n.auth.register_password_confirmation }}</label>
            <input
              v-model="form.password_confirmation"
              type="password"
              class="form-control"
              id="password_confirmation"
              name="password_confirmation"
              required
              autocomplete="password"
              @blur="validateStep(2)"
            />
            <p
              v-if="errors.password_confirmation != undefined"
              class="form-error-message"
            >{{ errors.password_confirmation[0] }}</p>
          </div>

          <!-- Policy acceptance check -->
          <div class="form-check">
            <label class="form-check-label">
              <input
                v-model="form.policy"
                class="form-check-input"
                id="policy"
                name="policy"
                type="checkbox"
                value="policy"
                required
                @change="validateStep(2)"
              />
              <span v-html="urls.policy"></span>
            </label>
          </div>

          <div class="form-group actions">
            <input
              type="hidden"
              name="lang"
              :value="locale"
            />
            <button
              type="submit"
              style="width: 100%"
              :disabled="getIsStepDisabled(3)"
              :class="{ 'btn btn-primary d-block': true, disabled: getIsStepDisabled(3) }"
              @click.stop="register"
            >{{ i18n.auth.register_action }}</button>
          </div>

          <div
            class="form-group"
            style="display:none"
          >
            <slot name="csrf"></slot>"
          </div>
        </div>
      </form>
      <div
        v-if="activeTab === 'step3'"
        class="card px-3 mt-3 signup-box"
      >
        Payment Goes Here!
      </div>
      <div class="row">
        <div class="col">
          <slot name="social-auth-buttons"></slot>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    charities: {
      type: [Array, Object],
      default: () => [],
    },
    urls: {
      type: Object,
      default: function () {
        return {
          terms: "/terms",
          privacy: "/privacy",
        };
      },
    },
    old: {
      type: Object,
      default: function () {
        return {
          charity_preference: null,
          mailbox_key: "",
          last_name: "",
          first_name: "",
          dob: "",
          email: "",
          policy: false,
        };
      },
    },
    config: {
      type: Object,
      default: () => {},
    },
    locale: { type: String, default: "en" },
    i18n: {
      type: [Object, Array],
      default: () => {},
    },
  },
  data() {
    return {
      activity: {
        busy: false,
      },
      form: {
        charity_preference: null,
        mailbox_key: null,
        first_name: null,
        last_name: null,
        password: null,
        password_confirmation: null,
        policy: false,
      },
      step: 0,
      errors: {},
      user: {},
      steps: [
        {
          label: "Select Username",
          value: 0,
          isInvalid: true,
        },
        {
          label: "Select Charity",
          value: 1,
          isInvalid: true,
        },
        {
          label: "Your Details",
          value: 2,
          isInvalid: true,
        },
        {
          label: "Payment",
          value: 3,
          isInvalid: true,
        },
      ],
    };
  },
  computed: {
    activeTab() {
      return `step${this.step}`;
    },
    isStep1Invalid() {
      return this.getIsStepDisabled(1);
    },
    isStep2Invalid() {
      return this.getIsStepDisabled(2);
    },
    isStep3Invalid() {
      return this.getIsStepDisabled(3);
    },
  },
  methods: {
    signup() {},
    next() {
      if (!this.getIsStepDisabled(this.step + 1)) {
        this.step += 1;
      }
    },
    changeStep(value) {
      this.step = value;
    },
    /* eslint-disable */
    getIsStepDisabled(step) {
      switch (step) {
        case 1:
          return this.steps[0].isInvalid || !this.form.mailbox_key;
        case 2:
          return this.steps[1].isInvalid || !this.form.charity_preference;
        case 3:
          return (
            this.activity.busy ||
            this.steps[2].isInvalid ||
            !this.form.first_name ||
            !this.form.last_name ||
            !this.form.dob ||
            !this.form.email ||
            !this.form.password ||
            !this.form.password_confirmation ||
            !this.form.policy
          );
      }
    },
    checkMailboxKey() {
      if (!this.activity.busy && (this.form.mailbox_key || "").length > 3) {
        this.activity.busy = true;
        axios
          .post("/api/register-validate/" + this.step, this.form)
          .then((response) => {
            if (response.data === true) {
              this.validateStep(0);
              this.errors = {};
            } else {
              this.invalidateStep(0);
              this.errors = response.data;
            }
          })
          .finally(() => {
            this.activity.busy = false;
          });
      } else {
        this.steps[0].isInvalid = true;
      }
    },
    validateStep(step) {
      this.steps[step].isInvalid = false;
    },
    invalidateStep(step) {
      this.steps[step].isInvalid = true;
    },
    recordForAbandonedCart() {
      if (!this.activity.busy && (this.form.email || "").length > 3) {
        this.activity.busy = true;
        axios
          .post("/api/abandoned-cart", {
            email: this.form.email,
            mailbox_key: this.form.mailbox_key,
            first_name: this.form.first_name,
            last_name: this.form.last_name,
          })
          .finally(() => {
            this.activity.busy = false;
          });
      }
    },
    register() {
      console.debug("Registering", this.form);
      this.activity.busy = true;
      axios
        .post("/api/register", this.form)
        .then((response) => {
          console.log("Registered", response);
          const result = response.data;
          if (!result.errors) {
            this.errors = result.errors;
          } else {
            this.user = result;
          }
          this.changeStep(3);
        })
        .catch((error) => {
          this.errors = error.response.data.errors || {};
        })
        .finally(() => {
          this.activity.busy = false;
        });
    },
  },
  created() {
    this.form = { ...this.form, ...this.old };
  },
};
</script>


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
  <div class="br3 ba b--gray-monica bg-white mb3 mt3">
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
            :key="index"
            :class="{
              'nav-item': true,
            }"
          >
            <a
              href="#"
              :class="{
                'nav-link': true,
                'active': item.value === step,
                disabled: getIsStepDisabled(item.value)
              }"
              @click.prevent="changeStep(item.value)"
            >
              <i
                v-if="!item.isInvalid"
                class="fa fa-check"
              ></i>&nbsp;{{ item.label }}
            </a>
          </li>
        </ul>
        <div
          v-if="activeTab === 'step0'"
          class="card px-3 mt-3 signup-box"
        >
          <h2>Step 1a: Enter Your Contact Information</h2>
          <label for="mailbox_key">{{ i18n.auth.register_mailbox_key }}</label>
          <div class="row">
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label for="first_name">{{ i18n.auth.register_firstname }}</label>
                <input
                  id="first_name"
                  v-model="form.first_name"
                  type="text"
                  class="form-control"
                  name="first_name"
                  :placeholder="i18n.auth.register_firstname_example"
                  required
                  autocomplete="given-name"
                  @blur="validateStep(0)"
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
                  id="last_name"
                  v-model="form.last_name"
                  type="text"
                  class="form-control"
                  name="last_name"
                  :placeholder="i18n.auth.register_lastname_example"
                  required
                  autocomplete="family-name"
                  @blur="validateStep(0)"
                />
                <p
                  v-if="errors.last_name != undefined"
                  class="form-error-message"
                >{{ errors.last_name[0] }}</p>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label for="email">{{ i18n.auth.register_email }}</label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="form-control"
                  name="email"
                  :placeholder="i18n.auth.register_email_example"
                  required
                  autocomplete="email"
                  autofocus
                  @blur="recordForAbandonedCart"
                  @input="validateStep(0)"
                />
                <p
                  v-if="errors.email != undefined"
                  class="form-error-message"
                >{{ errors.email[0] }}</p>
              </div>
            </div>
          </div>
          <h2>Step 1b: Select Your <strong>{{ config.domain }}</strong> Username</h2>
          <div class="row">
            <div
              v-if="welcomeBack"
              class="col col-12"
            >
              <div class="alert alert-info">
                Welcome back {{ form.first_name }}.
                <span v-if="!this.errors.mailbox_key">Your chosen mailbox is still available! Continue where you left of.</span><span v-else>Unfortunately your previously chosen mailbox is no longer available!</span>
              </div>
            </div>
            <div
              class="col col-sm-12 col-lg-7 col-md-7"
              style="padding-right: 5px; flex-grow: 1;"
            >
              <input
                id="mailbox_key"
                v-model="form.mailbox_key"
                type="text"
                class="form-control"
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
              class="col col-sm-12 d-flex align-center col-md-5 col-lg-5"
              style="align-items:center; padding-left: 5px; flex-shrink: 1;"
            >
              <span><strong>@ {{ config.domain }}</strong></span>
            </div>
            <div class="col col-12 form-group">
              <p
                v-if="errors.mailbox_key != undefined"
                class="form-error-message"
              >
                Username not available. Please enter another
              </p>
            </div>
          </div>
          <div class="row justify-end">
            <div
              class="col col-sm-12 col-md-6 d-flex align-center form-group"
              style="align-items:center; padding-left: 5px; justify-content: flex-end"
              @click.prevent="next"
            >
              <button
                :disabled="getIsStepDisabled(1)"
                :class="{ 'btn btn-info': true, disabled: getIsStepDisabled(1) }"
              >
                Next
              </button>
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
                id="charity-preference"
                class="card-body"
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
                      :id="`preference-${id}`"
                      v-model="form.charity_preference"
                      class="form-check-input"
                      type="radio"
                      name="charity_preference"
                      :value="id"
                      :selected="parseInt(old.charity_preference) == parseInt(id)"
                      @click="validateStep(1)"
                    />
                    {{ charity }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="row justify-end">
            <div
              class="col col-sm-12 col-md-6 d-flex align-center form-group"
              style="align-items:center; padding-left: 5px"
              @click.prevent="next"
            >
              <button
                :disabled="getIsStepDisabled(2)"
                :class="{ 'btn btn-info': true, disabled: getIsStepDisabled(2) }"
              >
                Next
              </button>
            </div>
          </div>
        </div>

        <div
          v-if="activeTab === 'step2'"
          class="card px-3 mt-3 signup-box"
        >
          <div class="row">
            <div class="col col-12">
              <h2>Step 3: Complete Your Account Details</h2>
            </div>
            <div class="col col-12">
              <div class="form-group">
                <label for="dob">{{ i18n.auth.register_dob }}</label>
                <input
                  id="dob"
                  v-model="form.dob"
                  type="date"
                  class="form-control"
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
            </div>
            <div class="col col-12">
              <div class="alert alert-info"><span v-html="i18n.auth.register_password_rules"></span></div>
              <div class="form-group">
                <label for="password">{{ i18n.auth.register_password }}</label>
                <input
                  id="password"
                  v-model="form.password"
                  type="password"
                  class="form-control"
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
            </div>
            <div class="col col-12">
              <div class="form-group">
                <label for="password_confirmation">{{ i18n.auth.register_password_confirmation }}</label>
                <input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  type="password"
                  class="form-control"
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
            </div>

            <div class="col col-12">
              <!-- Policy acceptance check -->
              <div class="form-check">
                <label class="form-check-label">
                  <input
                    id="policy"
                    v-model="form.policy"
                    class="form-check-input"
                    name="policy"
                    type="checkbox"
                    value="policy"
                    required
                    @change="validateStep(2)"
                  />
                  <span v-html="urls.policy"></span>
                </label>
              </div>
            </div>

            <div class="col col-12">
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
                >
                  {{ i18n.auth.register_action }}
                </button>
              </div>
              <div
                class="form-group"
                style="display:none"
              >
                <slot name="csrf"></slot>"
              </div>
            </div>
          </div>
        </div>
      </form>
      <div
        v-if="activeTab === 'step3'"
        class="card px-3 mt-3 signup-box"
      >
        <div class="row">
          <div class="col-12">
            <!-- <h2 class="tc mt4 fw4">{{ $t('settings.subscriptions_account_upgrade_title') }}</h2> -->
            <div class="br3 ba b--gray-monica bg-white mb4">
              <div class="pa4 bb b--gray-monica">
                <h3 class="tc">{{ $t('settings.subscriptions_account_payment') }}</h3>
                <div class="cf mb4">
                  <div class="fl w-100-ns w-100 pa3 mt0-ns mt4">
                    <div class="b--purple ba pt3 br3 bw1 relative">
                      <img
                        src="img/settings/subscription/best_value.png"
                        class="absolute"
                        style="top: -30px; left: -20px;"
                      />
                      <h3 class="tc mb3 pt3">{{ $t('settings.subscriptions_plan_year_title') }}
                      </h3>
                      <p class="tc">
                        <a
                          :href="urls.plans.annually"
                          class="btn btn-primary pv3"
                        >{{ $t('settings.subscriptions_plan_choose') }}</a>
                      </p>
                      <p class="tc mt2">
                        {{ $t('settings.subscriptions_plan_frequency_year', {
                          amount: plans.annually.friendlyPrice
                        })
                        }}
                      </p>
                      <ul class="mb4 center ph4">
                        <li class="mb3 relative ml4">
                          <svg
                            class="absolute"
                            style="left: -30px; top: -3px;"
                            width="26px"
                            height="26px"
                            viewBox="0 0 26 26"
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                          >
                            <defs />
                            <g
                              id="App"
                              stroke="none"
                              stroke-width="1"
                              fill="none"
                              fill-rule="evenodd"
                            >
                              <g id="Group-7">
                                <circle
                                  id="Oval-14"
                                  fill="#836BC8"
                                  cx="13"
                                  cy="13"
                                  r="13"
                                />
                                <polyline
                                  id="Path-16"
                                  stroke="#FFFFFF"
                                  stroke-width="2"
                                  points="6.95703125 13.2783203 11.5048828 17.7226562 21.0205078 7.75"
                                />
                              </g>
                            </g>
                          </svg>
                          {{ $t('settings.subscriptions_plan_year_bonus') }}
                        </li>
                      </ul>
                    </div>
                  </div>
                  <!-- <div class="fl w-50-ns w-100 pa3">
                    <div class="b--gray-monica ba pt3 br3 bw1">
                      <h3 class="tc mb3 pt3">{{ $t('settings.subscriptions_plan_month_title') }}
                      </h3>
                      <p class="tc">
                        <a
                          :href="urls.plans.monthly"
                          class="btn btn-primary pv3"
                        >{{ $t('settings.subscriptions_plan_choose') }}</a>
                      </p>
                      <p class="tc mt2">
                        {{ $t('settings.subscriptions_plan_frequency_month', {
                          amount: plans.monthly.friendlyPrice
                        })
                        }}
                      </p>
                      <ul class="mb4 center ph4">
                        <li class="mb3 relative ml4">
                          <svg
                            class="absolute"
                            style="left: -30px; top: -3px;"
                            width="26px"
                            height="26px"
                            viewBox="0 0 26 26"
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                          >
                            <defs />
                            <g
                              id="App"
                              stroke="none"
                              stroke-width="1"
                              fill="none"
                              fill-rule="evenodd"
                            >
                              <g id="Group-7">
                                <circle
                                  id="Oval-14"
                                  fill="#836BC8"
                                  cx="13"
                                  cy="13"
                                  r="13"
                                />
                                <polyline
                                  id="Path-16"
                                  stroke="#FFFFFF"
                                  stroke-width="2"
                                  points="6.95703125 13.2783203 11.5048828 17.7226562 21.0205078 7.75"
                                />
                              </g>
                            </g>
                          </svg>
                          {{ $t('settings.subscriptions_plan_month_bonus') }}
                        </li>
                      </ul>
                    </div>
                  </div> -->
                </div>
                <p class="mb1 tc">{{ $t('settings.subscriptions_plan_include1') }}</p>
                <p class="mb1 tc">{{ $t('settings.subscriptions_plan_include2') }}</p>
                <p class="mb1 tc">{{ $t('settings.subscriptions_plan_include3') }}</p>
              </div>
            </div>

            <h3 class="tc mb4 mt3">{{ $t('settings.subscriptions_help_title') }}</h3>
            <h4>{{ $t('settings.subscriptions_help_opensource_title') }}</h4>
            <p class="mb4">{{ $t('settings.subscriptions_help_opensource_desc') }}</p>

            <h4>{{ $t('settings.subscriptions_help_limits_title') }}</h4>
            <p class="mb4">
              {{ $t('settings.subscriptions_help_limits_plan', {
                number: config.monica.number_of_allowed_contacts_free_account
              })
              }}
            </p>

            <h4>{{ $t('settings.subscriptions_help_change_title') }}</h4>
            <p class="mb4">{{ $t('settings.subscriptions_help_change_desc') }}</p>
          </div>
        </div>
      </div>
      <!-- <div class="row">
        <div class="col">
          <slot name="social-auth-buttons"></slot>
        </div>
      </div> -->
    </div>
  </div>
</template>
<script>
import { validate } from "json-schema";

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
    existing: {
      type: Object,
      default: () => {},
    },
    plans: {
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
      welcomeBack: false,
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
        default:
          return (
            this.activity.busy ||
            !this.form.first_name ||
            !this.form.last_name ||
            !this.form.email ||
            !this.form.mailbox_key
          );
          break;
      }
    },
    checkMailboxKey() {
      if (!this.activity.busy && (this.form.mailbox_key || "").length > 3) {
        this.activity.busy = true;
        Promise.all([
          this.recordForAbandonedCart(true),
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
            }),
        ]);
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
    recordForAbandonedCart(force) {
      if (
        force ||
        (!this.activity.busy && (this.form.email || "").length > 3)
      ) {
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
        .post("/multi-step-register", {
          ...this.form,
          _token: this.getCsrfToken(),
        })
        .then((response) => {
          console.log("Registered", response);
          const result = response.data;
          if (result.errors) {
            this.errors = result.errors;
          } else if (result.id) {
            this.user = result;
            this.changeStep(3);
          }
        })
        .catch((error) => {
          this.errors = error.response.data.errors || {};
        })
        .finally(() => {
          this.activity.busy = false;
        });
    },
    getCsrfToken() {
      return document.querySelector('[name="_token"]').value;
    },
  },
  created() {
    this.form = { ...this.form, ...this.old };
    if (this.existing instanceof Object && this.existing.email) {
      this.welcomeBack = true;
      this.form.email = this.existing.email;
      this.form.first_name = this.existing.first_name;
      this.form.last_name = this.existing.last_name;
      this.form.mailbox_key = this.existing.mailbox_key;
      this.form.mailbox_key && this.checkMailboxKey();
      if (this.getIsStepDisabled(0)) {
        this.validateStep(0);
      }
    }
  },
};
</script>

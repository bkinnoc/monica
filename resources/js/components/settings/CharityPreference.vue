/* eslint-disable */
<template>
  <div class="ph3 pv3">
    <notifications
      group="charityPreferences"
      position="bottom right"
    />

    <h3 class="with-actions">
      {{ $t('settings.charity_preferences_title') }}
    </h3>
    <p>{{ $t('settings.charity_preferences_description') }}</p>

    <div
      v-if="limited"
      v-cy-name="'activity-type-premium-message'"
      class="mt3 mb3 form-information-message br2"
    >
      <div class="pa3 flex">
        <div class="mr3">
          <svg viewBox="0 0 20 20">
            <g fill-rule="evenodd">
              <circle
                cx="10"
                cy="10"
                r="9"
                fill="currentColor"
              />
              <path d="M10 0C4.486 0 0 4.486 0 10s4.486 10 10 10 10-4.486 10-10S15.514 0 10 0m0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8m1-5v-3a1 1 0 0 0-1-1H9a1 1 0 1 0 0 2v3a1 1 0 0 0 1 1h1a1 1 0 1 0 0-2m-1-5.9a1.1 1.1 0 1 0 0-2.2 1.1 1.1 0 0 0 0 2.2" />
            </g>
          </svg>
        </div>
        <div v-html="$t('settings.personalisation_paid_upgrade_vue', {url: 'settings/subscriptions' })"></div>
      </div>
    </div>

    <div v-cy-name="'activity-types'">
      <div class="dt dt--fixed w-100 collapse br--top br--bottom mt3">
        <!-- Charity Preference -->
        <div class="dt-row hover bb b--light-gray">
          <div class="dtc">
            <div class="pa2">
              <ul
                class="list-group"
                :id="'add-charity-id'"
              >
                <li
                  v-for="(option, index) in charityOptions"
                  style="padding: 8px; cursor: pointer"
                  :class="{
                      'list-group-item': true,
                      'active': savePreferenceForm.charity_id === option.id
                    }"
                  :key="index"
                >
                  <div class="form-check">
                    <label
                      :for="`preference-${option.id}`"
                      class="form-check-label"
                    >
                      <input
                        v-model="savePreferenceForm.charity_id"
                        class="form-check-input"
                        type="radio"
                        :id="`preference-${option.id}`"
                        :value="option.id"
                      />
                      {{ option.name }}
                    </label>
                  </div>
                </li>
              </ul>
            </div>
            <div class="dtc">
              <div class="pa2 b">
                <p
                  class="info"
                  v-html="charityMessage.replace(':percentage', percent)"
                ></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Charity -->
    <sweet-modal
      ref="
                    createCharityModal"
      overlay-theme="dark"
      :title="$t('settings.charity_preferences_modal_add')"
    >
      <form @submit.prevent="addCharity()">
        <div class="mb4">
          <p class="b mb2"></p>
          <!-- <form-select
            :id="'add-charity-id'"
            v-model="savePreferenceForm.charity_id"
            :required="true"
            :options="charityOptions.filter(c => selectedCharityIds.indexOf(c.id) === -1)"
            :input-props="{id:'autosuggest__input', placeholder:'Start searching?'}"
            :title="$t('settings.charity_preferences_modal_select_charity')"
            :validator="$v.savePreferenceForm.charity_id"
          /> -->
          <ul
            class="list-group"
            :id="'add-charity-id'"
          >
            <li
              v-for="(option, index) in charityOptions"
              style="padding: 8px; cursor: pointer"
              :class="{
                      'list-group-item': true,
                      'active': savePreferenceForm.charity_id === option.id
                    }"
              :key="index"
              @click="updateCharity(option)"
            >
              <div class="form-check">
                <label
                  :for="`preference-${option.id}`"
                  class="form-check-label"
                >
                  <input
                    v-model="savePreferenceForm.charity_id"
                    class="form-check-input"
                    type="radio"
                    :id="`preference-${option.id}`"
                    :value="option.id"
                  />
                  {{ option.name }}
                </label>
              </div>
            </li>
          </ul>
          <!-- <form-input
            :id="'update-charity-percent'"
            v-model="savePreferenceForm.percent"
            :input-type="'number'"
            :required="true"
            :title="`${$t('settings.charity_preferences_modal_set_percent')}. Max: ${maxAllowablePercentage}`"
            :validator="$v.savePreferenceForm.percent"
          /> -->
        </div>
      </form>
      <div slot="button">
        <a
          class="btn"
          href=""
          @click.prevent="closeCharityModal()"
        >
          {{ $t('app.cancel') }}
        </a>
        <a
          v-cy-name="'add-activity-type-charity-save-button'"
          class="btn btn-primary"
          href=""
          @click.prevent="addCharity()"
        >
          {{ $t('app.save') }}
        </a>
      </div>
    </sweet-modal>

    <!-- Update Charity -->
    <sweet-modal
      ref="updateCharityModal"
      overlay-theme="dark"
      :title="$t('settings.charity_preferences_modal_edit')"
    >
      <form @submit.prevent="updateCharity()">
        <div class="mb4">
          <p class="b mb2"></p>
          <h3>{{ updatedCharity.name }}</h3>
          <form-input
            :id="'update-charity-percent'"
            v-model="savePreferenceForm.percent"
            :input-type="'number'"
            :required="true"
            :title="`${$t('settings.charity_preferences_modal_set_percent')}. Max: ${maxAllowablePercentage}`"
            :validator="$v.savePreferenceForm.percent"
          />
        </div>
      </form>
      <div slot="button">
        <a
          class="btn"
          href=""
          @click.prevent="closeUpdateCharityModal()"
        >
          {{ $t('app.cancel') }}
        </a>
        <a
          v-cy-name="'update-activity-type-charity-button'"
          class="btn btn-primary"
          href=""
          @click.prevent="updateCharity()"
        >
          {{ $t('app.update') }}
        </a>
      </div>
    </sweet-modal>

    <!-- Delete Activiy type charity -->
    <sweet-modal
      ref="deleteCharityModal"
      overlay-theme="dark"
      :title="$t('settings.charity_preferences_modal_delete')"
    >
      <form>
        <div
          v-if="errorMessage !== ''"
          class="form-error-message mb3"
        >
          <div class="pa2">
            <p class="mb0">
              {{ errorMessage }}
            </p>
          </div>
        </div>
        <div class="mb4">
          <p class="mb2">
            {{ $t('settings.charity_preferences_modal_delete_desc') }}
          </p>
        </div>
      </form>
      <div slot="button">
        <a
          class="btn"
          href=""
          @click.prevent="closeDeleteCharityModal()"
        >
          {{ $t('app.cancel') }}
        </a>
        <a
          v-cy-name="'delete-charity-button'"
          class="btn btn-primary"
          href=""
          @click.prevent="destroyCharity()"
        >
          {{ $t('app.delete') }}
        </a>
      </div>
    </sweet-modal>
  </div>
</template>

<script>
import { validationMixin } from "vuelidate";
import { SweetModal } from "sweet-modal-vue";
import { required, maxValue, minValue } from "vuelidate/lib/validators";

export default {
  components: {
    SweetModal,
  },
  mixins: [validationMixin],

  props: {
    limited: {
      type: Boolean,
      default: false,
    },
    percent: {
      type: [Number, String],
      default: 30,
    },
    charityMessage: {
      type: String,
      default: "",
    },
  },

  data() {
    return {
      charityPreferences: [],
      charityOptions: [],
      errorMessage: "",

      updatedCharity: {
        id: "",
        name: "",
      },

      savePreferenceForm: {
        charity_id: null,
        percent: this.percent,
        errors: [],
      },

      destroycharityForm: {
        id: "",
        errors: [],
      },
    };
  },

  computed: {
    dirltr() {
      return this.$root.htmldir === "ltr";
    },
    selectedCharityIds() {
      return [this.charityPreferences.id];
    },
    maxAllowablePercentage() {
      const additional = this.updatedCharity?.pivot?.percent || 0;
      return this.maxPercentage == 0
        ? additional
        : this.maxPercentage + additional;
    },
  },
  validations() {
    return {
      savePreferenceForm: {
        charity_id: {
          required,
        },
        percent: {
          required,
          minValue: minValue(5),
          maxValue: maxValue(this.maxAllowablePercentage),
        },
      },
    };
  },

  mounted() {
    this.prepareComponent();
  },

  methods: {
    prepareComponent() {
      this.getCharities();
      this.getCharityPreference();
    },

    getAllowablePercentage(additional) {
      return this.maxPercentage == 0
        ? additional
        : this.maxPercentage + additional;
    },

    getCharityPreference() {
      axios.get("settings/charity/preference").then((response) => {
        this.charityPreferences = response.data || [].slice(0, 1);
        this.savePreferenceForm.charity_id = parseInt(response.data.id);
      });
    },

    getCharities() {
      axios.get("api/charities").then((response) => {
        this.charityOptions = response.data?.data || [];
      });
    },

    closeCharityModal() {
      this.$refs.createCharityModal.close();
    },

    closeDeleteCharityModal() {
      this.$refs.deleteCharityModal.close();
    },

    showCreateCharityModal() {
      this.savePreferenceForm.charity_id = null;
      this.savePreferenceForm.percent = 0;
      this.$refs.createCharityModal.open();
    },

    addCharity() {
      axios
        .post("settings/charity/preference", this.savePreferenceForm)
        .then((response) => {
          this.$refs.createCharityModal.close();
          const result =
            response.data instanceof Array ? response.data[0] : response.data;
          this.$root
            .storeUtils()
            .updateStateData(this.charityPreferences, result, true, true);
          this.savePreferenceForm.charity_id = null;
          this.savePreferenceForm.percent = 0;

          this.notify(this.$t("app.default_save_success"), true);
        })
        .catch((error) => {
          this.errorMessage = this.$root
            .storeUtils()
            .objectValues(error.response.data.errors)
            .join("<br/>");
        });
    },

    showEditCharityPreference(preference) {
      this.savePreferenceForm.id = preference.id;
      this.savePreferenceForm.charity_id = preference.pivot?.charity_id;
      this.savePreferenceForm.percent = preference.pivot?.percent;
      this.updatedCharity = preference;

      this.$refs.updateCharityModal.open();
    },

    showDeleteCharityPreference(charity) {
      this.destroycharityForm.id = charity.id;

      this.$refs.deleteCharityModal.open();
    },

    closeUpdateCharityModal() {
      this.$refs.updateCharityModal.close();
    },

    updateCharity() {
      axios
        .put(
          "settings/charity/preference/" + this.savePreferenceForm.charity_id,
          this.savePreferenceForm
        )
        .then((response) => {
          console.log("Result", response, this.$root.storeUtils());
          this.$refs.updateCharityModal.close();
          const result =
            response.data instanceof Array ? response.data[0] : response.data;
          console.log("Result", result);
          this.$root
            .storeUtils()
            .updateStateData(this.charityPreferences, result, true, true);
          this.updatedCharity = result;

          this.notify(this.$t("app.default_save_success"), true);
        })
        .catch((error) => {
          this.errorMessage = this.$root
            .storeUtils()
            .objectValues(error.response.data.errors)
            .join("<br/>");
        });
    },

    destroyCharity() {
      axios
        .delete("settings/charity/preference/" + this.destroycharityForm.id)
        .then((response) => {
          this.$refs.deleteCharityModal.close();
          this.destroycharityForm.id = "";
          this.charityPreferences = this.charityPreferences.filter(
            (c) => c.id != response.data.id
          );

          this.notify(this.$t("app.default_save_success"), true);
        })
        .catch((error) => {
          this.errorMessage = JSON.stringify(
            this.$root.apiUtils().resolveError(error)
          );
        });
    },

    notify(text, success) {
      this.$notify({
        group: "charityPreferences",
        title: text,
        text: "",
        type: success ? "success" : "error",
      });
    },
  },
};
</script>

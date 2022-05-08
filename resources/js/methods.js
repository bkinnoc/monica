import { BaseApi } from '@nitm/js-api-client-base';
import * as StoreUtils from '@nitm/js-api-client-base/dist/store/vuex/utils.js';
export default {
  /**
   * Update the default tab view.
   *
   * @param {string} view
   */
  updateDefaultProfileView (view) {
    axios.post('settings/updateDefaultProfileView', { name: view })
      .then(response => {
        this.global_profile_default_view = view;
      });
  },

  /**
   * Fix avatar in case img is on error.
   *
   * @param {event} event
   */
  fixAvatarDisplay (event) {
    event.srcElement.classList = ['hidden'];
    event.srcElement.nextElementSibling.classList.remove('hidden');
  },

  addToArray (array, result, matchFunc) {
    const index = array.findIndex(
      (c) => matchFunc instanceof Function ? matchFunc(c, result) : c.id == result.id
    );
    if (index > -1) {
      array.splice(index, 1, result);
    } else {
      array.push(result);
    }

    return array;
  },
  storeUtils () {
    return StoreUtils.default;
  },
  apiUtils () {
    const api = new BaseApi;
    return api.utils;
  }
};

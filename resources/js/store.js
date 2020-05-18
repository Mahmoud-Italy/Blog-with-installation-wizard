window.Vue = require('vue');
import Vuex from 'vuex'
import axios from 'axios'
import app from './app.js';

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    accessToken: null,
    loggingIn: false,
    loginError: null,
  },
  mutations: {
    loginStart: state => state.loggingIn = true,
    loginStop: (state, errorMessage) => {
      state.loggingIn = false;
      state.loginError = errorMessage;
    },
    updateAccessToken: (state, accessToken) => {
      state.accessToken = accessToken;
    },
    logout: (state) => {
      state.accessToken = null;
    },
    updateValue (state, value) {
      state.value = value
    }
  },
  actions: {
    updateValueAction ({ commit }, value) {
      commit('updateValue', value)
    },
    doLogin({ commit }, loginData) {
      commit('loginStart');

      axios.post('https://reqres.in/api/login', {
        //...loginData
      })
      .then(response => {
        localStorage.setItem('accessToken', response.data.token);
        commit('loginStop', null);
        commit('updateAccessToken', response.data.token);
        router.push('/users');
      })
      .catch(error => {
        commit('loginStop', error.response.data.error);
        commit('updateAccessToken', null);
      })
    },
    fetchAccessToken({ commit }) {
      commit('updateAccessToken', localStorage.getItem('accessToken'));
    },
    logout({ commit }) {
      localStorage.removeItem('accessToken');
      commit('logout');
      router.push('/login');
    }
  }
})
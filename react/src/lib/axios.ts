import Axios from 'axios';

const axios = Axios.create({
  baseURL: process.env.BACKEND_URL,
});

export default axios;

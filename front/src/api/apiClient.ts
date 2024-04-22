import axios from 'axios';

const apiClient = axios.create({
  baseURL: process.env.baseURL,
  headers: {
    "Content-type": "application/json",
  }
});


export default apiClient;
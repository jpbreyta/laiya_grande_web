import http from 'k6/http';
import { sleep } from 'k6';

export const options = {
  stages: [
    { duration: '30s', target: 20 },
    { duration: '1m', target: 20 },  
    { duration: '10s', target: 0 },
  ],
};

export default function () {
  http.get('http://127.0.0.1:8000/admin/booking');   
sleep(1);
}
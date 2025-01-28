import { createBrowserRouter } from 'react-router-dom';
import App from './App';
import DefaultLayout from '@/layouts/DefaultLayout';
import Profile from '@/pages/Profile';
import Overview from '@/pages/Overview';
import Details from '@/pages/Details';
import Register from './pages/Register';

const router = createBrowserRouter([
  {
    element: <DefaultLayout />,
    children: [
      {
        path: '/app',
        element: <App />,
      },
      {
        path: '/',
        element: <Overview />,
      },
      {
        path: '/details',
        element: <Details />,
      },
      {
        path: '/profile',
        element: <Profile />,
      }
    ],
  },
  {
    path: '/register',
    element: <Register />,
  },
]);

export default router;

import { useState } from 'react';
import type { ChangeEvent, FormEvent } from 'react';

function App() {
  const [file, setFile] = useState<File | null>(null);

  const getPreSignedURL = async (): Promise<string> => {
    const response = await fetch('http://localhost:8080/api/uploads', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
    });

    if (!response.ok) {
      throw new Error('Failed to get presigned URL');
    }

    const { key } = await response.json();
    return key;
  };

  const handleSubmit = async (event: FormEvent) => {
    event.preventDefault();

    if (!file) {
      console.error('No file selected');
      return;
    }

    try {
      const preSignedURL = await getPreSignedURL();

      const response = await fetch(preSignedURL, {
        method: 'PUT',
        body: file,
      });

      if (response.ok) {
        console.log('File uploaded successfully');
      } else {
        console.error('Failed to upload file to S3');
      }
    } catch (error) {
      console.error('Error uploading file:', error);
    }
  };

  const handleFileChange = (event: ChangeEvent<HTMLInputElement>) => {
    if (event.target.files?.[0]) {
      setFile(event.target.files[0]);
    }
  };

  return (
    <div>
      <form onSubmit={handleSubmit}>
        <input type="file" onChange={handleFileChange} />
        <button type="submit" disabled={!file}>
          Upload
        </button>
      </form>
    </div>
  );
}

export default App;

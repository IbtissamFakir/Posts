import React, { useEffect, useState } from 'react'
import PostCard from './PostCard';
import axios from 'axios';
function ListePosts() {
    const [posts, setPosts] = useState([])
    useEffect(() => {
        axios.get('http://127.0.0.1:8000/api/posts')
            .then((response) => {
                setPosts(response.data)
            })
            .catch((error) => {
                console.log(error)
            })
    }, [])

    return (
        <div className='mx-4 p-6 bg-gray-50 min-h-screen font-sans'>

            {
                posts.map(p =>
                    <PostCard key={p.id} post={p} />
                )
            }

        </div>
    )
}

export default ListePosts

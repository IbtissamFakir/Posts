import React, { useEffect, useState } from 'react'
import PostCard from './PostCard';
import PublierPost from './PublierPost';
import SearchBar from './SearchBar';
import axios from 'axios';

function ListePosts() {
    const [posts, setPosts] = useState([])
    const [searchTerm, setSearchTerm] = useState('')
    
    useEffect(() => {
        axios.get('http://127.0.0.1:8000/api/posts')
            .then((response) => {
                setPosts(response.data)
            })
            .catch((error) => {
                console.log(error)
            })
    }, [])

    // Fonction de filtrage des posts
    const filteredPosts = posts.filter(post => {
        if (!searchTerm.trim()) return true
        
        const search = searchTerm.toLowerCase()
        const titre = (post.titre || '').toLowerCase()
        const content = (post.content || '').toLowerCase()
        const author = (post.user?.nom_complet || '').toLowerCase()
        
        return titre.includes(search) || content.includes(search) || author.includes(search)
    })

    return (
        <div className='flex flex-col items-center min-h-screen font-sans pt-6 px-4'>
            <div className='w-full max-w-2xl'>
                {/* Barre de recherche */}
                <SearchBar searchTerm={searchTerm} onSearchChange={setSearchTerm} />
                
                {/* Formulaire pour publier */}
                <PublierPost />
                
                {/* Liste des posts */}
                <div className='space-y-4'>
                    {filteredPosts.length > 0 ? (
                        filteredPosts.map(p =>
                            <PostCard key={p.id} post={p} />
                        )
                    ) : (
                        <div className='text-center py-12'>
                            <p className='text-gray-500 text-lg'>Aucun post trouvé pour "{searchTerm}"</p>
                        </div>
                    )}
                </div>
            </div>
        </div>
    )
}

export default ListePosts

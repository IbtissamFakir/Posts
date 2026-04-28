import React, { useState } from "react";
import axios from "axios";
import { Send } from "lucide-react";

function CommentForm({ postId, refreshComments }) {
    const [content, setContent] = useState("");
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();

        if (!content.trim()) return;

        try {
            setLoading(true);

            await axios.post(
                `http://127.0.0.1:8000/api/posts/${postId}/commentaires`,
                {
                    content: content.trim(),
                    user_id: JSON.parse(localStorage.getItem("user"))?.id || 1
                }
            );

            setContent("");

            if (refreshComments) {
                refreshComments();
            }
        } catch (error) {
            console.error("Erreur ajout commentaire :", error);
        } finally {
            setLoading(false);
        }
    };

    return (
        <form
            onSubmit={handleSubmit}
            className="flex gap-3 mt-4 mb-4"
        >
            <input
                type="text"
                placeholder="Ajouter un commentaire..."
                value={content}
                onChange={(e) => setContent(e.target.value)}
                className="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 bg-white hover:border-gray-400 transition-colors placeholder-gray-400"
            />

            <button
                type="submit"
                disabled={loading}
                className="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-md hover:from-blue-700 hover:to-blue-800 transition-all disabled:opacity-50"
            >
                {loading ? (
                    <span>...</span>
                ) : (
                    <Send size={18} />
                )}
            </button>
        </form>
    );
}

export default CommentForm;
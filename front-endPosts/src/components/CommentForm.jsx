import React, { useState } from "react";
import axios from "axios";

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
            className="flex gap-3 mt-4"
        >
            <input
                type="text"
                placeholder="Écrire un commentaire..."
                value={content}
                onChange={(e) => setContent(e.target.value)}
                className="flex-1 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <button
                type="submit"
                disabled={loading}
                className="bg-blue-600 text-white px-5 py-3 rounded-xl text-sm font-medium hover:bg-blue-700 transition"
            >
                {loading ? "..." : "Envoyer"}
            </button>
        </form>
    );
}

export default CommentForm;
import React, { useEffect, useState } from "react";
import axios from "axios";
import CommentItem from "./CommentItem";
import toast from "react-hot-toast";

function CommentList({ postId }) {
    const [comments, setComments] = useState([]);
    const [loading, setLoading] = useState(true);

    // Charger commentaires
    const fetchComments = async () => {
        try {
            setLoading(true);

            const res = await axios.get(
                `http://127.0.0.1:8000/api/posts/${postId}/commentaires`
            );

            setComments(res.data);
        } catch (error) {
            console.error("Erreur chargement commentaires :", error);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        if (postId) {
            fetchComments();
        }
    }, [postId]);

    // Suppression
    const handleDelete = async (id) => {
    console.log("DELETE ID =", id);

    try {
        await axios.delete(
            `http://127.0.0.1:8000/api/posts/${postId}/commentaires/${id}`
        );

        // 🔥 update UI direct
        setComments((prev) => prev.filter((c) => c.id !== id));

        toast.success("Commentaire supprimé");
    } catch (error) {
        console.log("DELETE ERROR:", error.response?.data);

        // 🔥 IMPORTANT : ne pas casser UI
        toast.error("Impossible de supprimer ce commentaire");
    }
};

    // Modification
    const handleUpdate = async (id, newContent) => {
        try {
            await axios.put(
                `http://127.0.0.1:8000/api/posts/${postId}/commentaires/${id}`,
                {
                    content: newContent,
                }
            );

            setComments((prev) =>
                prev.map((c) =>
                    c.id === id ? { ...c, content: newContent } : c
                )
            );
        } catch (error) {
            console.error("Erreur modification :", error);
        }
    };
    // signaler
    const handleSignal = async (id, description) => {
        try {
            const user = JSON.parse(localStorage.getItem("user"));

            await axios.post(
                `http://127.0.0.1:8000/api/posts/${postId}/commentaires/${id}/signaler`,
                {
                    description: description,
                    user_id: user?.id || 1, // 🔥 FIX IMPORTANT
                }
            );

            toast.success("Commentaire signalé");
        } catch (error) {
            toast.error(error.response?.data?.message || "Erreur signalement");
        }
    };
       return (
        <div className="mt-2 space-y-1">
            {comments.length > 0 && (
                <div className="px-4 pt-2">
                    <span className="text-xs font-bold text-slate-400 uppercase tracking-widest">
                        Commentaires
                    </span>
                </div>
            )}

            {loading ? (
                <div className="p-5 text-center text-slate-400 text-xs">Chargement...</div>
            ) : (
                <div className="divide-y divide-slate-50">
                    {comments.map((comment) => (
                        <CommentItem key={comment.id} data={comment} onDelete={handleDelete} onUpdate={handleUpdate} onSignal={handleSignal} />
                    ))}
                </div>
            )}
        </div>
    );
}
export default CommentList;
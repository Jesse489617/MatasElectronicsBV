import type { ImageAsset } from '@/types/resources/core/ImageAsset';

export interface Component {
    id: number;
    name: string;
    manufacturer_id: number;
    type: string;
    image: ImageAsset | null;
    price: number;
}

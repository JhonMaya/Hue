<?php exit() ?>--by sida 81.170.95.141
local ItemsToBuy = {}
local ShopRange = 1250
local ShopLocation = Vector(GetShop().x, GetShop().y, GetShop().z)
local ShopKey = "P"

function OnTick()
	if IsKeyPressed(string.byte(ShopKey)) then
		Packet('R_WAYPOINTS', {wayPoints = {[myHero.networkID] = {{x=ShopLocation.x, y=ShopLocation.z}}}}):receive()
	end
	--[[Buy the queued items]]
	if GetDistance(ShopLocation) < ShopRange and GetDistance(ShopLocation) > 400 then
		while #ItemsToBuy > 0 do
			Packet("PKT_BuyItemReq", {itemId = ItemsToBuy[1]}):send()
			table.remove(ItemsToBuy, 1)
		end
	end
end

function OnSendPacket(p)
	if p.header == Packet.headers.PKT_BuyItemReq then
		local packet = Packet(p)
		local itemId = packet:get("itemId") 
		if itemId then
			table.insert(ItemsToBuy, itemId)
		end
		p:Block()
	end
end

function GetBasketGold()
	local result = 0
		for i, item  in ipairs(ItemsToBuy) do
			local ditem = GetItem(item)
			if ditem then
				result = result + ditem.gold.total
			end	
		end
	return result
end

function OnDraw()
	if #ItemsToBuy > 0 then
		DrawText("Shopping Basket ("..GetBasketGold().."g)", 16, 10, 10, ARGB(255,255,255,255))
	end
	local xpos = 10
	local ypos = 20
	for i, item  in ipairs(ItemsToBuy) do
		local ditem = GetItem(item)
		if ditem then
			local dsprite = ditem:GetSprite()
			local name = ditem:GetName()
			local gold = ditem.gold.total
			ypos = ypos +  20
			dsprite:SetScale(0.25, 0.25)
			dsprite:Draw(10, ypos - 6, 255)
			local color = ARGB(255,255,255,255)
			if not CursorIsUnder(xpos, ypos, 100, 13) then
				color = ARGB(255, 255, 255, 255)
			else
				color = ARGB(255, 255, 0, 0)
			end
			if not name then name = "" end
			DrawText(name.." ("..gold.."g)", 13, xpos + 16, ypos, color)
		end
	end
	--DrawCircle(ShopLocation.x, ShopLocation.y, ShopLocation.z, 400, ARGB(255,255,255,255))
	--DrawCircle(ShopLocation.x, ShopLocation.y, ShopLocation.z, ShopRange, ARGB(255,255,255,255))
end

function OnWndMsg(Msg, Key)
	if Msg == WM_LBUTTONDOWN then
		local xpos = 10
		local ypos = 20
		local i = 1
		while i <= #ItemsToBuy do
			ypos = ypos +  20
			if not CursorIsUnder(xpos, ypos, 100, 13) then
				i = i + 1
			else
				table.remove(ItemsToBuy, i)
			end
		end
	end
end